<?php

namespace App\Http\Controllers;

use App\Models\ApproveRequest;
use App\Models\AssignRequest;
use App\Models\Department;
use App\Models\Form;
use App\Models\Organization;
use App\Models\RequestFile;
use App\Models\Role;
use App\Models\Template;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;


class ReportController extends Controller
{
    public function getFilterData(Request $request)
    {
        $employees = [];
        $allEmployees = [];
        $organization_id = $request->organization_id;
        $organizations = Organization::select('id', 'name')->get();
        $departments = Department::select('id', 'name')->get();
        $templates = Template::select('id', 'name')->get();
        if ($organization_id) {
            $forms = AssignRequest::whereHas('form.template', function ($q) use ($organization_id) {
                $q->where('organization_id', '=', $organization_id);
                $q->orWhereNull('organization_id');
            })->get();
            foreach ($forms as $form) {
                $user = User::select('id', 'name')->where('id', $form->user_id)->where('organization_id', $organization_id)->first();
                if ($user)
                    $allEmployees[] = $user;
            }
            $employees = array_unique($allEmployees);
        } else {
            $users = User::select('id', 'name')->with('roles')->get();
            foreach ($users as $user) {
                if ($user->roles->contains('name', 'Employee')) {
                    $employees[] = $user;
                }
            }
        }
        return response()->json(['organizations' => $organizations, 'departments' => $departments
            , 'templates' => $templates, 'employees' => $employees]);
    }

    public function reportCards()
    {
        $user = auth()->user();
        if ($user->id == 1) {
            $requests = Form::all();
            $openedRequests = Form::where('status', '!=', 'pending')->get();
            $closedRequests = Form::where('status', 'closed')->get();
            $homeRequests = Form::limit(10)->get();
            $logs = Activity::limit(25)->get();
        } else {
            $requests = Form::where('user_id', $user->id)->get();
            $openedRequests = Form::where('status', '!=', 'pending')->where('user_id', $user->id)->get();
            $closedRequests = Form::where('status', 'closed')->where('user_id', $user->id)->get();
            $homeRequests = Form::where('user_id', $user->id)->limit(10)->get();
            $logs = Activity::where('causer_id', $user->id)->limit(25)->get();
        }

        $data = [];
        if ($user->hasAnyRole(['Root', 'Admin']))
            $data["users"] = User::all()->count();
        else
            $data["users"] = 0;

        $data["templates"] = Template::all()->count();
        $data["requests"] = $requests->count();
        $data["roles"] = Role::all()->count();
        $data["openedRequests"] = $openedRequests->count();
        $data["closedRequests"] = $closedRequests->count();
        $data["homeRequests"] = $homeRequests;
        $data["logs"] = $logs;

        try {
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function getForms(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $organization_id = $request->organization_id;
        $template_id = $request->template_id;
        $department_id = $request->department_id;

        $allData = [];
        $templatesData = [];
        $closedTemplatesData = [];
        $templatesLabels = [];

        if (($from && $to) || $organization_id || ($organization_id && $department_id) || $template_id) {
            $forms = Form::when($from, function ($query) use ($from, $to) {
                $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
            })
                ->when($template_id, function ($query) use ($template_id) {
                    $query->where('template_id', '=', $template_id);
                })
                ->when($organization_id, function ($query) use ($organization_id) {
                    $query->whereHas('user', function ($q) use ($organization_id) {
                        $q->where('organization_id', '=', $organization_id);
                    });
                })
                ->when($department_id, function ($query) use ($department_id) {
                    $query->whereHas('user', function ($q) use ($department_id) {
                        $q->where('department_id', '=', $department_id);
                    });
                })
                ->where('parent','=',0)->get();
//            return $forms;
        }else{
            $forms = Form::where('parent','=',0)->get();
        }
        foreach ($forms as $form) {
            $createdAt = $form->created_at;
            $closedDate = RequestFile::where('form_id', $form->id)->pluck('created_at');
            if (count($closedDate)) {
                $created = Carbon::parse($createdAt);
                $closed = Carbon::parse($closedDate[0]);
                $days = ($closed->diffInDays($created) == 0 ? 1 : $closed->diffInDays($created));
            } else {
                $days = 0;
            }
            $allData = $this->findAndUpdateForm($form->name, $allData, $days);
        }
        try {
            foreach ($allData as $data) {
                $templatesData[] = intval($data['days']/$data['closed']);
                $closedTemplatesData[] = $data['closed'];
                $templatesLabels[] = strtok($data['name'], " ");;
            }
            return response()->json(['data' => $allData, 'templatesData' => $templatesData
                , 'closedTemplatesData' => $closedTemplatesData, 'labels' => $templatesLabels]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    private function findAndUpdateForm($form, $array, $days)
    {
        $in = 0;
        foreach ($array as $key => $arr) {
            if ($form == $arr['name'] and $days) {
                $in = 1;
                $array[$key]['days'] += $days;
                $array[$key]['closed'] += 1;
            }
        }
        if (!$in and $days) {
            array_push($array, [
                'name' => $form,
                'days' => $days,
                'closed' => 1
            ]);
        }
        return $array;
    }

    public function getEmployees()
    {
        $data = [];
        $allData = [];
        $users = User::all();
        foreach ($users as $user) {
            $processingRequests = [];
            $rejectedRequests = [];
            $forms = AssignRequest::where('user_id', $user->id)->with('form')->get();

            foreach ($forms as $form) {
                if($form->form)
                {
                    if($form->form->status === "processing")
                    {
                        $processingRequests[] = $form;
                    }
                    elseif($form->form->status === "rejected")
                    {
                        $rejectedRequests[] = $form;
                    }
                }

                $assignedDate = $form->date;
                $closedDate = RequestFile::where('form_id', $form->form_id)->pluck('created_at');
                if (count($closedDate) && $assignedDate) {
                    $assigned = Carbon::parse($assignedDate);
                    $closed = Carbon::parse($closedDate[0]);
                    $days = ($closed->diffInDays($assigned) == 0 ? 1 : $closed->diffInDays($assigned));
                } else {
                    $days = 0;
                }

                $allData = $this->findAndUpdateUser($user->name, $allData, $days);
            }

            if(isset($allData[$user->name])){
                $allData[$user->name]['processing'] = count($processingRequests) ?? 0;
                $allData[$user->name]['rejected'] = count($rejectedRequests) ?? 0;
            }
        }

        try {
            return response()->json(array_values($allData));
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    private function findAndUpdateUser($user, $array, $days)
    {
        $in = 0;
        foreach ($array as $key => $arr) {
            if ($user == $arr['name'] and $days) {
                $in = 1;
                $array[$key]['days'] += $days;
                $array[$key]['closed'] += 1;
            }
        }
        if (!$in and $days) {
            $array[$user] = [
                'name' => $user,
                'days' => $days,
                'closed' => 1,
            ];
        }
        return $array;
    }

    public function requestChart(Request $request)
    {
        $data = [];
        $user = auth()->user();
        $from = $request->from;
        $to = $request->to;
        $organization_id = $request->organization_id;
        $template_id = $request->template_id;
        $department_id = $request->department_id;
        $employee_id = $request->employee_id;


        //employee
        $employeeData = [];
        $query = DB::table('users')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->where('model_has_roles.role_id', '=', 3)
            ->select('users.id', 'users.name')
            ->groupBy('users.id');
        $employees = $query->get();

        //admins
        $adminData = [];
        $adminQuery = DB::table('users')
            ->leftJoin('user_organizations', 'user_organizations.user_id', '=', 'users.id')
            ->leftJoin('user_templates', 'user_templates.user_id', '=', 'users.id')
            ->select('users.id', 'users.name','users.organization_id','users.department_id')
            ->where('users.id', '!=', 1)
            ->groupBy('users.id');

        if ($organization_id || ($organization_id && $department_id) || $employee_id) {
            $admins = $adminQuery->when($organization_id, function ($query) use ($organization_id) {
                $query->where('users.organization_id', '=', $organization_id);
            })->when($department_id, function ($query) use ($department_id) {
                $query->where('users.department_id', '=', $department_id);
            })->when($employee_id, function ($query) use ($employee_id) {
                $query->where('users.id', '=', $employee_id);
            })->get();
        } else {
            $admins = $adminQuery->get();
        }

        if (($from && $to) || $organization_id || ($organization_id && $department_id) || $template_id || $employee_id) {
            if ($user->hasRole('Root')) {
                $data[] = Form::when($from, function ($query) use ($from, $to) {
                    $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                    ->when($template_id, function ($query) use ($template_id) {
                        $query->where('template_id', '=', $template_id);
                    })
                    ->when($organization_id, function ($query) use ($organization_id) {
                        $query->whereHas('template', function ($q) use ($organization_id) {
                            $q->where('organization_id', '=', $organization_id);
                        });
                    })
                    ->where('parent','=',0)
                    ->where('status', 'pending')->get()->count();
                $data[] = Form::when($from, function ($query) use ($from, $to) {
                    $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                    ->when($template_id, function ($query) use ($template_id) {
                        $query->where('template_id', '=', $template_id);
                    })
                    ->when($organization_id, function ($query) use ($organization_id) {
                        $query->whereHas('template', function ($q) use ($organization_id) {
                            $q->where('organization_id', '=', $organization_id);
                        });
                    })
                    ->where('parent','=',0)
                    ->where('status', 'approved')->get()->count();
                $data[] = Form::when($from, function ($query) use ($from, $to) {
                    $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                    ->when($template_id, function ($query) use ($template_id) {
                        $query->where('template_id', '=', $template_id);
                    })
                    ->when($organization_id, function ($query) use ($organization_id) {
                        $query->whereHas('template', function ($q) use ($organization_id) {
                            $q->where('organization_id', '=', $organization_id);
                        });
                    })
                    ->where('parent','=',0)
                    ->where('status', 'processing')->get()->count();
                $data[] = Form::when($from, function ($query) use ($from, $to) {
                    $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                    ->when($template_id, function ($query) use ($template_id) {
                        $query->where('template_id', '=', $template_id);
                    })
                    ->when($organization_id, function ($query) use ($organization_id) {
                        $query->whereHas('template', function ($q) use ($organization_id) {
                            $q->where('organization_id', '=', $organization_id);
                        });
                    })
                    ->where('parent','=',0)
                    ->where('status', 'rejected')->get()->count();
                $data[] = Form::when($from, function ($query) use ($from, $to) {
                    $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                    ->when($template_id, function ($query) use ($template_id) {
                        $query->where('template_id', '=', $template_id);
                    })
                    ->when($organization_id, function ($query) use ($organization_id) {
                        $query->whereHas('template', function ($q) use ($organization_id) {
                            $q->where('organization_id', '=', $organization_id);
                        });
                    })
                    ->where('parent','=',0)
                    ->where('status', 'returned')->get()->count();
                $data[] = Form::when($from, function ($query) use ($from, $to) {
                    $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
                })
                    ->when($template_id, function ($query) use ($template_id) {
                        $query->where('template_id', '=', $template_id);
                    })
                    ->when($organization_id, function ($query) use ($organization_id) {
                        $query->whereHas('template', function ($q) use ($organization_id) {
                            $q->where('organization_id', '=', $organization_id);
                        });
                    })
                    ->where('parent','=',0)
                    ->where('status', 'closed')->get()->count();

                if ($employee_id) {
                    $daysArray = [];
                    $averageDays = 0;
                    $employeeName = User::where('id', $employee_id)->pluck('name');
                    $forms = AssignRequest::when($employee_id, function ($query) use ($employee_id) {
                        $query->where('user_id', '=', $employee_id);
                    })
                        ->when($organization_id, function ($query) use ($organization_id) {
                            $query->whereHas('user', function ($q) use ($organization_id) {
                                $q->where('organization_id', '=', $organization_id);
                            });
                        })
                        ->when($department_id, function ($query) use ($department_id) {
                            $query->whereHas('user', function ($q) use ($department_id) {
                                $q->where('department_id', '=', $department_id);
                            });
                        })->get();

                    foreach ($forms as $form) {
                        $assignedDate = $form->date;
                        $closedDate = RequestFile::when($from, function ($query) use ($from, $to) {
                            $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                            ->when($template_id, function ($query) use ($template_id) {
                                $query->whereHas('form', function ($q) use ($template_id) {
                                    $q->where('template_id', '=', $template_id);
                                });
                            })
                            ->where('form_id', $form->form_id)->pluck('created_at');
                        if (count($closedDate) && $assignedDate) {
                            $assigned = Carbon::parse($assignedDate);
                            $closed = Carbon::parse($closedDate[0]);
                            $days = ($closed->diffInDays($assigned) == 0 ? 1 : $closed->diffInDays($assigned));
                        } else {
                            $days = 0;
                        }
                        $daysArray[] = $days;
                    }
                    $daysArray = array_filter($daysArray);
                    if ($daysArray) {
                        if (count($daysArray)) {
                            $averageDays = array_sum($daysArray) / count($daysArray);
                        }
                        $employeeData[] = ['x' => $employeeName, 'y' => ceil($averageDays)];
                    }
                } else {
                    foreach ($employees as $employee) {
                        $daysArray = [];
                        $averageDays = 0;
                        $forms = AssignRequest::when($organization_id, function ($query) use ($organization_id) {
                            $query->whereHas('user', function ($q) use ($organization_id) {
                                $q->where('organization_id', '=', $organization_id);
                            });
                        })
                            ->when($department_id, function ($query) use ($department_id) {
                                $query->whereHas('user', function ($q) use ($department_id) {
                                    $q->where('department_id', '=', $department_id);
                                });
                            })->where('user_id', $employee->id)->get();
                        foreach ($forms as $form) {
                            $assignedDate = $form->date;
                            $closedDate = RequestFile::when($from, function ($query) use ($from, $to) {
                                $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
                            })
                                ->when($template_id, function ($query) use ($template_id) {
                                    $query->whereHas('form', function ($q) use ($template_id) {
                                        $q->where('template_id', '=', $template_id);
                                    });
                                })
                                ->where('form_id', $form->form_id)->pluck('created_at');
                            if (count($closedDate) && $assignedDate) {
                                $assigned = Carbon::parse($assignedDate);
                                $closed = Carbon::parse($closedDate[0]);
                                $days = ($closed->diffInDays($assigned) == 0 ? 1 : $closed->diffInDays($assigned));
                            } else {
                                $days = 0;
                            }
                            $daysArray[] = $days;
                        }
                        $daysArray = array_filter($daysArray);
                        if ($daysArray) {
                            if (count($daysArray)) {
                                $averageDays = array_sum($daysArray) / count($daysArray);
                            }
                            $employeeData[] = ['x' => $employee->name, 'y' => ceil($averageDays)];
                        }
                    }
                }

                foreach ($admins as $admin) {
                    $adminDaysArray = [];
                    $averageAdminDays = 0;

                    $forms = Form::when($from, function ($query) use ($from, $to) {
                        $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                        ->when($template_id, function ($query) use ($template_id) {
                            $query->where('template_id', '=', $template_id);
                        })->get();

                    if ($forms->count() > 0) {
                        foreach ($forms as $form) {
                            $submissionDate = $form->created_at;
                            $assignedRequestDate = AssignRequest::where('form_id', $form->id)->where('assigner_id', $admin->id)->pluck('created_at');
                            if (count($assignedRequestDate) && $submissionDate) {
                                $submission = Carbon::parse($submissionDate);
                                $assigned = Carbon::parse($assignedRequestDate[0]);
                                $adminDays = ($assigned->diffInDays($submission) == 0 ? 1 : $assigned->diffInDays($submission));
                            } else {
                                $adminDays = 0;
                            }
                            $adminDaysArray[] = $adminDays;
                        }
                        $adminDaysArray = array_filter($adminDaysArray);
                    }

                    if ($adminDaysArray) {
                        if (count($adminDaysArray)) {
                            $averageAdminDays = array_sum($adminDaysArray) / count($adminDaysArray);
                        }
                        $adminData[] = ['x' => $admin->name, 'y' => ceil($averageAdminDays)];
                    }
                }
            } else {
                $data[] = Form::whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"])->where('user_id', $user->id)->where('parent','=',0)->where('status', 'pending')->get()->count();
                $data[] = Form::whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"])->where('user_id', $user->id)->where('parent','=',0)->where('status', 'approved')->get()->count();
                $data[] = Form::whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"])->where('user_id', $user->id)->where('parent','=',0)->where('status', 'processing')->get()->count();
                $data[] = Form::whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"])->where('user_id', $user->id)->where('parent','=',0)->where('status', 'rejected')->get()->count();
                $data[] = Form::whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"])->where('user_id', $user->id)->where('parent','=',0)->where('status', 'returned')->get()->count();
                $data[] = Form::whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"])->where('user_id', $user->id)->where('parent','=',0)->where('status', 'closed')->get()->count();
            }
        } else {
            if ($user->hasRole('Root')) {
                $data[] = Form::where('status', 'pending')->where('parent','=',0)->get()->count();
                $data[] = Form::where('status', 'approved')->where('parent','=',0)->get()->count();
                $data[] = Form::where('status', 'processing')->where('parent','=',0)->get()->count();
                $data[] = Form::where('status', 'rejected')->where('parent','=',0)->get()->count();
                $data[] = Form::where('status', 'returned')->where('parent','=',0)->get()->count();
                $data[] = Form::where('status', 'closed')->where('parent','=',0)->get()->count();
                foreach ($employees as $employee) {
                    $daysArray = [];
                    $averageDays = 0;
                    $forms = AssignRequest::where('user_id', $employee->id)->get();
                    foreach ($forms as $form) {
                        $assignedDate = $form->date;
                        $closedDate = RequestFile::where('form_id', $form->form_id)->pluck('created_at');
                        if (count($closedDate) && $assignedDate) {
                            $assigned = Carbon::parse($assignedDate);
                            $closed = Carbon::parse($closedDate[0]);
                            $days = ($closed->diffInDays($assigned) == 0 ? 1 : $closed->diffInDays($assigned));
                        } else {
                            $days = 0;
                        }
                        $daysArray[] = $days;
                    }
                    $daysArray = array_filter($daysArray);
                    if ($daysArray) {
                        if (count($daysArray)) {
                            $averageDays = array_sum($daysArray) / count($daysArray);
                        }
                        $employeeData[] = ['x' => $employee->name, 'y' => ceil($averageDays)];
                    }
                }
                foreach ($admins as $admin) {
                    $adminDaysArray = [];
                    $averageAdminDays = 0;

                    $forms = AssignRequest::where('assigner_id', $admin->id)->get();
                    if ($forms->count() > 0) {
                        foreach ($forms as $form) {
                            $submissionDate = $form->created_at;
                            $assignedRequestDate = AssignRequest::where('form_id', $form->id)->pluck('created_at');
                            if (count($assignedRequestDate) && $submissionDate) {
                                $submission = Carbon::parse($submissionDate);
                                $assigned = Carbon::parse($assignedRequestDate[0]);
                                $adminDays = ($assigned->diffInDays($submission) == 0 ? 1 : $assigned->diffInDays($submission));
                            } else {
                                $adminDays = 0;
                            }
                            $adminDaysArray[] = $adminDays;
                        }
                    }

                    $adminDaysArray = array_filter($adminDaysArray);
                    if ($adminDaysArray) {
                        if (count($adminDaysArray)) {
                            $averageAdminDays = array_sum($adminDaysArray) / count($adminDaysArray);
                        }
                        $adminData[] = ['x' => $admin->name, 'y' => ceil($averageAdminDays)];
                    }

                }
            } else {
                $data[] = Form::where('user_id', $user->id)->where('parent','=',0)->where('status', 'pending')->get()->count();
                $data[] = Form::where('user_id', $user->id)->where('parent','=',0)->where('status', 'approved')->get()->count();
                $data[] = Form::where('user_id', $user->id)->where('parent','=',0)->where('status', 'processing')->get()->count();
                $data[] = Form::where('user_id', $user->id)->where('parent','=',0)->where('status', 'rejected')->get()->count();
                $data[] = Form::where('user_id', $user->id)->where('parent','=',0)->where('status', 'returned')->get()->count();
                $data[] = Form::where('user_id', $user->id)->where('parent','=',0)->where('status', 'closed')->get()->count();
            }
        }
        try {
            return response()->json(['data' => $data, 'employeeData' => $employeeData, 'adminData' => $adminData]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }

    }

    public function getServiceClosed()
    {
        $templates = Template::with(
            ['forms' => function ($form) {
                $form->where('status', 'closed');
            }],
        )->get();

        foreach ($templates as $key => $template) {
            /*check of closed request */
            $data[$key]['id'] = $template->id;
            $data[$key]['name'][] = $template->name;
            $data[$key]['name'][] = 'Employees';
            $data[$key]['total'][] = (self::getTempClosed($template->id)) ? self::getTempClosed($template->id)->total : 0;
            $data[$key]['total'][] = count(Self::emps());
        }
        return response()->json($data);
    }

    public function getEmpRep()
    {
        $data = [];
        /* get employee number has $type of request / all employee */
        $data['closed'][] = count(self::reportsOfEmployee('closed'));
        $data['closed'][] = count(Self::emps());

        $data['rejected'][] = count(self::reportsOfEmployee('rejected'));
        $data['rejected'][] = count(Self::emps());

        $data['processing'][] = count(self::reportsOfEmployee('processing'));
        $data['processing'][] = count(Self::emps());

        $data['approved'][] = count(self::reportsOfEmployee('approved'));
        $data['approved'][] = count(Self::emps());


//        $data['data']['rejected'] = round((count(self::reportsOfEmployee('rejected')) / count(Self::emps())) * 100, 0);
//        $data['data']['processing'] = round((count(self::reportsOfEmployee('processing')) / count(Self::emps())) * 100, 0);
//        $data['data']['approved'] = round((count(self::reportsOfEmployee('approved')) / count(Self::emps())) * 100, 0);
        return response()->json($data);
    }

    public static function reportsOfEmployee($filter = null)
    {
        $data = DB::table('forms')
            ->where("forms.status", "=", $filter)
            ->get();
        return $data;
    }

    public static function getTempClosed($template_id)
    {
        $data = DB::table('forms')->select(DB::raw('count(*) as total'))
//            ->join("assign_requests","assign_requests.form_id","=","forms.id")
//            ->groupBy("assign_requests.user_id")
            ->where(["forms.status" => "closed", "forms.template_id" => $template_id])
            ->first();
        return $data;
    }

    public static function emps()
    {
        $employees = [];
        $users = User::with('roles')->where('name', '!=', 'ROOT')->get();
        foreach ($users as $user) {
            if ($user->roles->contains('name', 'Employee')) {
                $employees[] = $user;
            }
        }
        return $employees;
    }

    public function costData(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $organization_id = $request->organization_id;
        $department_id = $request->department_id;
        $data = [];
        if (($from && $to) || $organization_id || ($organization_id && $department_id)) {
            $data['convicted'][] = (self::getlegtitonClosed($from,$to,$organization_id,$department_id)) ? (int)self::getlegtitonClosed($from,$to,$organization_id,$department_id)->total : 0; /*get total*/
            $data['convicted'][] = (self::getlegtitonClosed('convicted',$from,$to,$organization_id,$department_id)) ? (int)self::getlegtitonClosed('convicted',$from,$to,$organization_id,$department_id)->total : 0; /*get convicted*/
            $data['condemned'][] = (self::getlegtitonClosed($from,$to,$organization_id,$department_id)) ? (int)self::getlegtitonClosed($from,$to,$organization_id,$department_id)->total : 0; /*get total*/
            $data['condemned'][] = (self::getlegtitonClosed('condemned',$from,$to,$organization_id,$department_id)) ? (int)self::getlegtitonClosed('condemned',$from,$to,$organization_id,$department_id)->total : 0;
        }else{
            $data['convicted'][] = (self::getlegtitonClosed()) ? (int)self::getlegtitonClosed()->total : 0; /*get total*/
            $data['convicted'][] = (self::getlegtitonClosed('convicted')) ? (int)self::getlegtitonClosed('convicted')->total : 0; /*get convicted*/
            $data['condemned'][] = (self::getlegtitonClosed()) ? (int)self::getlegtitonClosed()->total : 0; /*get total*/
            $data['condemned'][] = (self::getlegtitonClosed('condemned')) ? (int)self::getlegtitonClosed('condemned')->total : 0;
        }
        return response()->json($data);
    }

    public static function getlegtitonClosed($filter = null,$from = null,$to = null,$organization_id = null,$department_id = null)
    {
        $data = RequestFile::select(DB::raw("SUM(expected_result) as total"))
            ->when($from, function ($query) use ($from, $to) {
                $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when($organization_id, function ($query) use ($organization_id) {
                $query->whereHas('form', function ($q) use ($organization_id) {
                    $q->whereHas('user', function ($Qbuilder) use ($organization_id) {
                        $Qbuilder->where('organization_id', '=', $organization_id);
                    });
                });
            })
            ->when($department_id, function ($query) use ($department_id) {
                $query->whereHas('form', function ($q) use ($department_id) {
                    $q->whereHas('user', function ($Qbuilder) use ($department_id) {
                        $Qbuilder->where('department_id', '=', $department_id);
                    });
                });
            });
        if ($filter) {
            $data = $data->where("company_judgment", "LIKE", "%the_company_is_" . $filter . "%");
        }
        $data = $data->with(['form' => function($query){
            $query->where("name", "LIKE", "%Litigation%");
        }])->first();
        return $data;
    }

    public function litigationRequests(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $organization_id = $request->organization_id;
        $department_id = $request->department_id;

        //closed litigation
        if (($from && $to) || $organization_id || ($organization_id && $department_id)) {
            $totalLitigationForms = Form::when($from, function ($query) use ($from, $to) {
                $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
            })->when($organization_id, function ($query) use ($organization_id) {
                $query->whereHas('user', function ($q) use ($organization_id) {
                    $q->where('organization_id', '=', $organization_id);
                });
            })
                ->when($department_id, function ($query) use ($department_id) {
                    $query->whereHas('user', function ($q) use ($department_id) {
                        $q->where('department_id', '=', $department_id);
                    });
                })
                ->where('template_id', 2)->where('parent','=',0)->get()->count();

            $query = RequestFile::when($from, function ($query) use ($from, $to) {
                $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
            })
                ->when($organization_id, function ($query) use ($organization_id) {
                    $query->whereHas('form', function ($q) use ($organization_id) {
                        $q->whereHas('user', function ($Qbuilder) use ($organization_id) {
                            $Qbuilder->where('organization_id', '=', $organization_id);
                        });
                    });
                })
                ->when($department_id, function ($query) use ($department_id) {
                    $query->whereHas('form', function ($q) use ($department_id) {
                        $q->whereHas('user', function ($Qbuilder) use ($department_id) {
                            $Qbuilder->where('department_id', '=', $department_id);
                        });
                    });
                });

            $convictedRequests = $query->where('company_judgment', '=', 'the_company_is_convicted')->get()->count();
            $condemnedRequests = $query->where('company_judgment', '=', 'the_company_is_condemned')->get()->count();

        }else{
            $totalLitigationForms = Form::where('template_id', 2)->where('parent','=',0)->get()->count();
            $convictedRequests = RequestFile::where('company_judgment', '=', 'the_company_is_convicted')->get()->count();
            $condemnedRequests = RequestFile::where('company_judgment', '=', 'the_company_is_condemned')->get()->count();
        }
        $convictedData = [$totalLitigationForms, $convictedRequests];
        $condemnedData = [$totalLitigationForms, $condemnedRequests];
        return response()->json(['convictedData' => $convictedData, 'condemnedData' => $condemnedData]);
    }

    public function contractRequest(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $organization_id = $request->organization_id;
        $department_id = $request->department_id;
        $data = array();
        if (($from && $to) || $organization_id || ($organization_id && $department_id)) {
            $total_request = Form::when($from, function ($query) use ($from, $to) {
                $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
            })
                ->when($organization_id, function ($query) use ($organization_id) {
                    $query->whereHas('user', function ($q) use ($organization_id) {
                        $q->where('organization_id', '=', $organization_id);
                    });
                })
                ->when($department_id, function ($query) use ($department_id) {
                    $query->whereHas('user', function ($q) use ($department_id) {
                        $q->where('department_id', '=', $department_id);
                    });
                })
                ->where('name', 'LIKE', '%Contract%')->where('parent','=',0)->get()->count();
            $data['review'][] = self::getContractRequests('Review',$from,$to,$organization_id,$department_id);
            $data['prepare'][] = self::getContractRequests('Prepare',$from,$to,$organization_id,$department_id);
        }else {
            $total_request = Form::where('name', 'LIKE', '%Contract%')->where('parent','=',0)->get()->count();
            /*review type*/
            $data['review'][] = self::getContractRequests('Review');
            $data['prepare'][] = self::getContractRequests('Prepare');
        }
        $data['review'][] = $total_request;
        $data['prepare'][] = $total_request;

        return response()->json($data, 200);
    }

    public static function getContractRequests($type = null,$from = null,$to = null,$organization_id = null,$department_id = null)
    {
        $contracts = Form::where('name', 'LIKE', '%Contract%')->where('parent','=',0)
            ->when($from, function ($query) use ($from, $to) {
                $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
            })->when($organization_id, function ($query) use ($organization_id) {
                $query->whereHas('user', function ($q) use ($organization_id) {
                    $q->where('organization_id', '=', $organization_id);
                });
            })
            ->when($department_id, function ($query) use ($department_id) {
                $query->whereHas('user', function ($q) use ($department_id) {
                    $q->where('department_id', '=', $department_id);
                });
            });
        if ($type) {
            $contracts = $contracts->whereHas('pages.items.filling', function ($q) use ($type) {
                $q->where('value', 'LIKE', '%' . $type . '%');
            })->get();
        }else{
            $contracts = $contracts->get();
        }

        return $contracts->count();
    }

    public function litigationTypes(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $organization_id = $request->organization_id;
        $department_id = $request->department_id;

        $data = [];
        $procedureTypes = [];
        if (($from && $to) || $organization_id || ($organization_id && $department_id)) {
            $queryFilter = ApproveRequest::when($from, function ($query) use ($from, $to) {
                $query->whereRaw("created_at >= ? AND created_at <= ?", [$from . " 00:00:00", $to . " 23:59:59"]);
            })
                ->when($organization_id, function ($query) use ($organization_id) {
                    $query->whereHas('form', function ($q) use ($organization_id) {
                        $q->whereHas('user', function ($Qbuilder) use ($organization_id) {
                            $Qbuilder->where('organization_id', '=', $organization_id);
                        });
                    });
                })
                ->when($department_id, function ($query) use ($department_id) {
                    $query->whereHas('form', function ($q) use ($department_id) {
                        $q->whereHas('user', function ($Qbuilder) use ($department_id) {
                            $Qbuilder->where('department_id', '=', $department_id);
                        });
                    });
                });

            $data[] = $queryFilter->where('workers', '=', true)->get()->count();
            $data[] = $queryFilter->where('commercial', '=', true)->get()->count();
            $data[] = $queryFilter->where('general', '=', true)->get()->count();
            $data[] = $queryFilter->where('administrative', '=', true)->get()->count();
            $data[] = $queryFilter->where('executive', '=', true)->get()->count();

            $procedureTypes[] = $queryFilter->where('procedureType', '=', 'litigate')->get()->count();
            $procedureTypes[] = $queryFilter->where('procedureType', '=', 'civil_defense')->get()->count();
            $procedureTypes[] = $queryFilter->where('procedureType', '=', 'reconciliation')->get()->count();
        }else{
            $data[] = ApproveRequest::where('workers', '=', true)->get()->count();
            $data[] = ApproveRequest::where('commercial', '=', true)->get()->count();
            $data[] = ApproveRequest::where('general', '=', true)->get()->count();
            $data[] = ApproveRequest::where('administrative', '=', true)->get()->count();
            $data[] = ApproveRequest::where('executive', '=', true)->get()->count();

            $procedureTypes[] = ApproveRequest::where('procedureType', '=', 'litigate')->get()->count();
            $procedureTypes[] = ApproveRequest::where('procedureType', '=', 'civil_defense')->get()->count();
            $procedureTypes[] = ApproveRequest::where('procedureType', '=', 'reconciliation')->get()->count();
        }
        try {
            return response()->json(['data' => $data, 'procedureTypes' => $procedureTypes]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }
}
