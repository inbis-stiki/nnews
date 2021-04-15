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
            'Authorization: key=AAAA4WGGD4A:APA91bGfFC5rZ1LlCF4IJyuqjcYsAIh0TzYwciDWM6UsPs9ltVpfS9Aon1dzNqwn_frrjaa7aF9d37H2vPf25icnR60TgVm1hOieGYskyZG2tvS8uoBPDV0F-QcJcVpJM1G6aZOsvHUz',
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