<?php
class Notification{
	private $title;
    private $message;
    private $id_news;
 
	public function setTitle($title){
		$this->title = $title;
	}
 
	public function setMessage($message){
		$this->message = $message;
	}

	public function setId_news($id_news){
		$this->id_news = $id_news;
	}
	
    public function getNotifications(){
        $notification = array();
        $notification['title'] = $this->title;
        $notification['body'] = $this->message;
        $notification['id_news'] = $this->id_news;
        return $notification;
    }

    public function pushNotification($firebase_token, $requestData){
        $fields = array(
            'to' => $firebase_token,
            'data' => $requestData
        );
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
            'Authorization: key=AAAAMQulW_g:APA91bHqbbdXCX47ZxyOn32F_HIXIiYr2m694M8eHG4ciITOySIuISkFoxG0JztGJRIGcpTf9HzvUpjdvOA2lj0LPgb-pYnQRSF4cQw2wrGtBDg0Jws1r8LvP3qvySlgbcN-zsDY-SGo',
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if($result === FALSE){
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
}
?>