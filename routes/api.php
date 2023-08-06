<?php

use Illuminate\Support\Facades\Route;

/**
 * Routes for authentication users only
 */

Route::get('getLoginSetting','SettingController@getLoginSetting');

Route::group(['middleware' => ['auth:api', 'scope:admin']], function () {
    Route::get('auth/me', 'User\MeController@getMe');
    Route::post('auth/logout', 'Auth\LoginController@logout');

    Route::get('translations', 'HomeController@translations');
    Route::put('translations', 'HomeController@saveTranslations');

    Route::post('updateProfile', 'User\MeController@updateProfileInfo');

    Route::group(['prefix' => 'admin'], function () {
        Route::get('getAdminOrganizations', 'Auth\UserController@getAdminOrganizations');

        /****** Users ******/
        // permissions
        Route::group(['prefix' => 'permissions', 'namespace' => 'Auth'], function () {
            Route::get('', 'PermissionController@index')->name('permissions.index');
            Route::post('', 'PermissionController@store')->name('permissions.store');
            Route::put('', 'PermissionController@update')->name('permissions.update');
            Route::delete('{id}', 'PermissionController@destroy')->name('permissions.destroy');
        });
        // roles
        Route::group(['prefix' => 'roles', 'namespace' => 'Auth'], function () {
            Route::get('', 'RoleController@index')->name('roles.index');
            Route::get('all', 'RoleController@getAllRoles');
            Route::get('{id}', 'RoleController@getRole');
            Route::post('', 'RoleController@store')->name('roles.store');
            Route::put('', 'RoleController@update')->name('roles.update');
            Route::delete('{ids?}', 'RoleController@destroy')->name('roles.destroy');
        });
        // activity logs
        Route::group(['prefix' => 'activitylogs', 'namespace' => 'Auth'], function () {
            Route::get('', 'ActivitylogController@index');
        });
        /****** Request Form ******/
        Route::group(['prefix' => 'forms', 'namespace' => 'Forms'], function () {
            Route::get('', 'formsController@index')->name('forms.index');
            Route::get('get/{id}', 'formsController@getFormById')->name('getForm');
            Route::post('store_form', 'formsController@fillsRequest')->name('forms.fillsRequest');
            Route::post('update_form','formsController@update_fills')->name('name.update_fills');
            Route::get('/request-reviews', 'formsController@index');
            Route::delete('{ids?}', 'formsController@destroy');
            Route::post('restore/{ids?}', 'formsController@restore');
            Route::post('/assign-request', 'formsController@assignRequest');
            Route::post('/reject-request', 'formsController@rejectRequest');
            Route::post('/assigned', 'formsController@deleteAssignedUser');
            Route::put('/update-assigned-request', 'formsController@editAssignedRequest');
            Route::get('/assigned/{id?}', 'formsController@getAssignedRequest');
            Route::get('action', 'formsController@action');
            Route::post('approve', 'formsController@approve');
            Route::get('approve-secret', 'formsController@approveSecret');
            Route::post('return_request', 'formsController@return_request');
            Route::post('value_request','formsController@RequestValue');
            Route::get('formsAssigned','formsController@getAssigned');
            Route::get('/history','formsController@history');
            Route::post('close_request','formsController@endRequest');
            Route::get('user_permission','formsController@getUserPer');
            Route::get('employees','formsController@getEmployeeUsers');
            Route::get('employees_Available','formsController@getEmployeeWithoutper');
            Route::get('getFillterFormData','formsController@getFillterFormData');
            Route::get('filterByDate','formsController@filterByDate');
//            Route::get('formsReview','formsController@formsReview');
        });
        /****** End Request Form ******/
        /****** Assign Request Form ******/
        Route::group(['prefix' => 'assigned-forms', 'namespace' => 'Forms'], function () {
            Route::get('', 'AssignFormController@index');
            Route::get('{id?}', 'AssignFormController@edit')->name('getAssignedForm');
        });
        /****** End Assign Request Form ******/
        /****** closed Request Form ******/
        Route::group(['prefix' => 'closed-forms', 'namespace' => 'Forms'], function () {
            Route::get('', 'closedFormController@index');
//            Route::get('{id?}', 'AssignFormController@edit')->name('getAssignedForm');
        });
        /****** End closed Request Form ******/
        // users
        Route::group(['prefix' => 'users', 'namespace' => 'Auth'], function () {
            Route::get('', 'UserController@index')->name('users.index');
            Route::get('getWithTrashed', 'UserController@getWithTrashed');
            Route::get('{id}', 'UserController@getUser');
            Route::get('ldapUsers/{q?}', 'UserController@ldapUsers');
            Route::post('', 'UserController@store')->name('users.store');
            Route::put('', 'UserController@update')->name('users.update');
            Route::delete('{ids?}', 'UserController@destroy')->name('users.destroy');
            Route::post('restore/{ids?}', 'UserController@restore');
            Route::put('change-user-password', 'UserController@changeUserPassword');
        });
        // organizations
        Route::group(['prefix' => 'organizations', 'namespace' => 'Setting'], function () {
            Route::get('/', 'OrganizationController@index');
            Route::get('get/all', 'OrganizationController@getAllOrganization');
            Route::get('{id}', 'OrganizationController@getOrganization');
            Route::post('', 'OrganizationController@store');
            Route::put('', 'OrganizationController@update');
            Route::delete('{ids?}', 'OrganizationController@destroy')->name('organizations.destroy');
            Route::post('restore/{ids?}', 'OrganizationController@restore');
        });
        // departments
        Route::group(['prefix' => 'departments', 'namespace' => 'Setting'], function () {
            Route::get('/', 'DepartmentController@index');
            Route::get('getAllDepartments', 'DepartmentController@getAllDepartments');
            Route::get('{id}', 'DepartmentController@getDepartment');
            Route::post('', 'DepartmentController@store');
            Route::put('', 'DepartmentController@update');
            Route::delete('{ids?}', 'DepartmentController@destroy')->name('departments.destroy');
            Route::post('restore/{ids?}', 'DepartmentController@restore');
        });
        /****** Dynamic Forms ******/
        // templates
        Route::group(['prefix' => 'templates'], function () {
            // index
            Route::get('/', 'TemplatesController@index');
            Route::get('permissions', 'TemplatesController@permissions');
            // get all
            Route::get('get/all', 'TemplatesController@getAll');
            // get all
            Route::get('all', 'TemplatesController@all');
            // create
            Route::post('/', 'TemplatesController@store');
            // get
            Route::get('{template}/get', 'TemplatesController@get');
            // update
            Route::put('{template}', 'TemplatesController@update');

            Route::post('updateTemplate', 'TemplatesController@updateTemplate');
            // delete
            Route::delete('{ids?}', 'TemplatesController@destroy');
            // get
            Route::get('get/users/{q?}', 'TemplatesController@getUsers');
            Route::get('get/users/{q?}', 'TemplatesController@getUsers');
            // assign users
            Route::post('{template}/assignUsers', 'TemplatesController@assignUsers');
            Route::post('restore/{ids?}', 'TemplatesController@restore');
            Route::post('assign','TemplatesController@assign');
            Route::get('getUserOrganization','TemplatesController@getUserOrganization');
            Route::get('getUserTemplates','TemplatesController@getUserTemplates');

        });

        Route::group(['prefix' => 'settings'], function () {
            // settings
            Route::get('/', 'SettingController@index');
            Route::put('/', 'SettingController@updateSettings');
            // terms
            Route::get('terms', 'SettingController@terms');
            Route::put('terms', 'SettingController@termsUpdate');
            // applyingToJob
            Route::get('generalSettings', 'SettingController@generalSettings');
            Route::put('generalSettings', 'SettingController@generalSettingsUpdate');

            Route::post('uploader','SettingController@upload');
        });
        // admin search
        // Route::group(['prefix' => 'search'], function () {
        //     Route::get('/', 'SearchController@index');
        // });
        // // admin change mail notify
        // Route::group(['prefix' => 'admin'], function () {
        //     Route::get('changeMailNotify', 'UsersController@changeMailNotify');
        // });
        // // mail templates
        // Route::group(['prefix' => 'mailtemplates'], function () {
        //     // index
        //     Route::get('/', 'MailTemplateController@index');
        //     // create
        //     Route::post('/', 'MailTemplateController@store');
        //     // get all
        //     Route::get('all', 'MailTemplateController@all');
        //     // get
        //     Route::get('{template}', 'MailTemplateController@get');
        //     // update
        //     Route::put('{template}', 'MailTemplateController@update');
        //     // delete
        //     Route::delete('/', 'MailTemplateController@bulkDelete');
        //     // restore
        //     Route::post('bulkRestore', 'MailTemplateController@bulkRestore');
        // });
        Route::get('legalUsers/{requesterIds?}','Auth\UserController@getLegalUsers');

        //Reports
        Route::group(['prefix' => 'reports'], function () {
            Route::get('/reportCards', 'ReportController@reportCards');
            Route::get('/requestChart', 'ReportController@requestChart');
            Route::get('/employeeChart', 'ReportController@employeeChart');
            Route::get('/getEmployees', 'ReportController@getEmployees');
            Route::get('/getForms', 'ReportController@getForms');
            Route::get('/employeeReport', 'ReportController@getEmpRep');
            Route::get('/serviceClosed', 'ReportController@getServiceClosed');
            Route::get('/litigationCost', 'ReportController@costData');
            Route::get('/litigationRequests', 'ReportController@litigationRequests');
            Route::get('/contractRequests', 'ReportController@contractRequest');
            Route::get('/litigationTypes', 'ReportController@litigationTypes');
            Route::get('/getFilterData', 'ReportController@getFilterData');
        });
    });

    // User profile updates
    Route::put('settings/profile', 'User\SettingController@updateProfile');
    Route::put('settings/password', 'User\SettingController@updatePassword');

    /****** Uploader *****/
    Route::group(['prefix' => 'uploader'], function () {
        // upload
        Route::post('/', 'UploadController@store');
    });

});


/**
 * Routes for Public access
 */
Route::group(['middleware' => 'guest:api', 'prefix' => 'auth', 'namespace' => 'Auth'], function () {
    // Route::post('register', 'RegisterController@register');
    Route::post('verification/verify/{user}', 'VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend', 'VerificationController@resend')->name('verification.resend');
    Route::post('login', 'LoginController@login');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reset');
});
