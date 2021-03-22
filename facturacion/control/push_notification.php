<?php 
$titulo = $_POST['titulo'];
$subtitulo = $_POST['subtitulo'];

	function send_notification ($tokens, $message)
	{
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
			'registration_ids' => $tokens,
			'data' => $message
			);
		
		$headers = array(
			'Authorization:key = AAAAIKQb7c8:APA91bGOY9Erd-qyghVLlmIRCjuPmiPsi800VeIdTBUlr07O2P1ZXVddeLwzr9Qjtm-1LEAQf_9Erkz2W7x8UJ42eUu-WrED4YvNzkySknYCfjIYMov-ROSPKBlTrgLc90fWfHt2to2P',
			'Content-Type: application/json');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		$result = curl_exec($ch);
		if($result === FALSE){
			die('Curl failed: '. curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}

	$conn = mysqli_connect("localhost","root","","fcm");

	$sql = "SELECT Token FROM user";

	$result = mysqli_query($conn,$sql);
	$tokens = array();

	if(mysqli_num_rows($result) > 0){

		while ($row = mysqli_fetch_assoc($result)) {
			$tokens[] = $row["Token"];
		}
	}
	mysqli_close($conn);

	$message = array("message" => $titulo,"titulo" => $subtitulo);
	$message_status = send_notification($tokens, $message);
	echo $message_status;
	echo json_encode($message_status);

 ?>