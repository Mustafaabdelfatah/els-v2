<?php
namespace App\Repositories\Eloquent;

use App\Http\Resources\SubscribeResource;
use App\Models\Package;
use App\Models\PayLog;
use App\Models\UserPackage;
use App\Models\User;
use App\Repositories\Contracts\ISubscribe;
use Carbon\Carbon;
use Exception;
use DB;

use App\Services\Payments\PaypalService;

class SubscribeRepository extends BaseRepository implements ISubscribe
{
    public function model()
    {
        return UserPackage::class;
    }
    public function createNewSubscription($id)
    {
        $package = Package::find($id);
        if ($package->price == 0) {
            return $this->storeNewSubscription($package->id);
        } else {
            $params = [
                'id'=> $package->id,
                'price'=>$package->price,
                'name'=>$package->name,
                'desc'=>$package->desc,
            ];
            $package = new PaypalService();
            $link = $package->createPayment($params);
            return $link;
        }
    }
    public function CheckPayment($params)
    {
        $check = new PaypalService();
        $result = $check->success($params);
        $packageName = $result->transactions[0]->item_list->items[0]->name ;
        $package = Package::where('name', $packageName)->where('is_used', false)->first();
        $subscribe = $this->storeNewSubscription($package->id);
        $params['primary_id'] = $this->primaryID;
        $params['user_id'] = auth()->user()->id;
        $params['method'] = 'paypal';
        PayLog::create($params);

        return new SubscribeResource($subscribe);
    }
    public function storeNewSubscription($id)
    {
        return $this->model::updateOrCreate(
            [
                'user_id'=> $this->primaryID
            ],
            [
                'subscribed_date' => Carbon::now(),
                'package_id' => $id,
                'status' => true,
            ]
        );
    }
    public function assignNewSubscription($userid, $package_id)
    {
        try {
            $user = User::find($userid);
            if (primaryID() === 1 and $user->primary_id == null) {
                return $this->model::updateOrCreate(
                    [
                        'user_id'=> $userid
                    ],
                    [
                        'subscribed_date' => Carbon::now(),
                        'package_id' => $package_id,
                        'status' => true,
                    ]
                );
            }
        } catch (Exception $e) {
            throw $e;
        }
    } // end assignNewSubscription

    public function unsubscribeUser($primary_id)
    {
        try {
            $user = User::find($primary_id);
            if (primaryID() === 1 and $user->primary_id == null) {
                return $user->unsubscribe($primary_id);
                // $user->package()->delete();
                // $count =  UserPackage::where('package_id', $package_id)->count();
                // $package = Package::where('id', $package_id)->first();
                // if ($package->is_used == true and $count === 0) {
                //     $package->delete();
                // }
            }
        } catch (Exception $e) {
            throw $e;
        }
    } // end unsubscribeUser

    

    public function UserActivePackage($primary_id)
    {
        return $this->getModelClass()->where('user_id', $primary_id)->where('status', true)->first();
    }
    public function upgrade($package_id, $status)
    {
        $current = Package::find($package_id);
        $check = $status == 'upgrade' ? '>' : '<';
        $packages = Package::where('is_used', false)->where('expire_date', '>', Carbon::now())->where('id', '!=', $package_id)->where('price', $check, $current->price)->get();
        if ($packages->count() > 0) {
            return  $packages;
        }
        $packages = Package::where('is_used', false)->where('expire_date', '>', Carbon::now())->get();
        return  $packages;
    }
}
