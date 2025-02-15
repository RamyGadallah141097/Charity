<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\OrderRepresentative;
use App\Models\Provider;
use App\Models\Representative;
use App\Models\RoomMessages;
use App\Models\User;
use App\Models\Token;
use App\Models\Notification as NotificationModel;

Trait  NotificationTrait
{
    private $serverKey = 'AAAAzsc5O8E:APA91bH5bELhMTm9ru_lVm_GrFfq0jjIakeGXuF8UtvhGkgtSAZpTIqBM4lCAL5H-Vue5y8bcOHvMlY932uiIGwFytg0VBG99n5w9g91A_WPPB7TbwWJ9ZmFR5DC1L8j-8FE5FJ46EQo';

    public function sendBasicNotification($title,$body,$order_id, $user_id=null,$provider_id = null,$type = null,$representative_id = null,$rev_id = null)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $order = Order::find($order_id);

        $storeData = [
            'title' => $title,
            'body' => $body,
            'order_id' => $order_id,
            'status' => $order->status??'',
            'provider_id' => ($provider_id) ?? $type,
            'user_id' => $user_id,
            'representative_id' => $rev_id,
        ];

        NotificationModel::create($storeData);

        if ($provider_id == null)
        {
            if ($order->status == 'new' || $order->status == 'offered'){
                $provider = Provider::find($type);
            }
            else
                $provider = Provider::find($order->provider_id);
        }else{
            $provider = Provider::find($provider_id);
        }



        $data = [
            'title' => $title,
            'body' => $body,
            'status' => $order->status??'',
            'order_id' => $order_id,
            'provider' => $provider,
            'user' => User::find($order->user_id),
            'representative' => Representative::find($rev_id),
            'notification_type'=>'basic',
        ];
//        return $data;


//        $query['user_id'] = $user_id;
//        $query['provider_id'] = $provider_id;
//        $query['representative_id'] = $representative_id;

        $tokens = [];
        if($user_id)
            $tokens = Token::where('user_id',$user_id)->pluck('token')->toArray();
        if($provider_id)
            $tokens = array_merge($tokens,Token::where('provider_id',$provider_id)->pluck('token')->toArray());
        if($representative_id)
            $tokens = array_merge($tokens,Token::where('representative_id',$representative_id)->pluck('token')->toArray());

//        return Token::where('provider_id',$provider_id)->pluck('token')->toArray();
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $data,
//            'notification' =>$data,
        );
        $headers = array(
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function sendChatNotification($data, $user_id=null,$provider_id = null,$representative_id = null)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $query['user_id'] = $user_id;
        $query['provider_id'] = $provider_id;
        $query['representative_id'] = $representative_id;

//        return $query;
        $tokens = Token::where($query)->pluck('token')->toArray();
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $data,
//            'notification' =>$data,
        );
        $fields = json_encode($fields);

        $headers = array(
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

}//end trait
