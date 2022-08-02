<?php

date_default_timezone_set("Asia/Bangkok");

$date = date("Y-m-d");

$time = date("H:i:s");

$json = file_get_contents('php://input');

$request = json_decode($json, true);

$message = $request["queryResult"]["queryText"];

$unknown = $request["queryResult"]["action"];

$user_id = $request['originalDetectIntentRequest']['payload']['data']['source']['userId'];

//$myfile = fopen(“log_dg.txt”, “w”) or die(“Unable to open file!”);

//$log = $date.”-”.$time.”\t”.$userId.”\t”.$queryText.”\n”;

//fwrite($myfile,$json);

//fclose($myfile);

$noti_token = 'line noitfy token';

date_default_timezone_set("Asia/Bangkok");

$serverName = "localhost";

$userName = "root";

$userPassword = "password";

$dbName = "linechatbot";

$opts = [

"http" =>[

"header" => "Content-Type: application/json\r\n".'Authorization: Bearer line_token'

]

];

$context = stream_context_create($opts);

$profile_json = file_get_contents('https://api.line.me/v2/bot/profile/'.$user_id,false,$context);

$profile_array = json_decode($profile_json,true);

$pic_ = $profile_array[pictureUrl];

$name_ = $profile_array[displayName];

$mass = $user_id.','.$message.','.$name_;

$message_all = '[LineChatbot] '.$name_.' ถามว่า '.$message.' '.'https://localhost/push1-1.php?mass='.$mass;

$date_ = date("Y-m-d");

$time_ = date("H:i:s");

$content = $date_.' '.$time_.' '.$name_.' '.$user_id.' '.$pic_.' '.$message."\n";

if($unknown=="input.unknown"){

$chOne = curl_init();

curl_setopt( $chOne, CURLOPT_URL, "https:\\notify-api.line.me/api/notify");

// SSL USE

curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0);

curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt( $chOne, CURLOPT_POST, 1);

curl_setopt( $chOne, CURLOPT_POSTFIELDS, $message);

curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=$message_all&imageThumbnail=$pic_&imageFullsize=$pic_");

curl_setopt( $chOne, CURLOPT_FOLLOWLOCATION, 1);

$headers = array( "Content-type: application/x-www-form-urlencoded", "Authorization: Bearer ".$noti_token, );

curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);

curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1);

$result = curl_exec( $chOne );

if(curl_error($chOne)) { echo "error:" . curl_error($chOne); }

else { $result_ = json_decode($result, true);

//echo “status : “.$result_[‘status’];

//echo "message : ". $result_["message"];

}

curl_close( $chOne );

}else{

}

$connect = mysqli_connect($serverName,$userName,$userPassword,$dbName) or die ("connect error".mysqli_error());

mysqli_set_charset($connect, "utf8");

$query = "INSERT INTO chatbot_log(user_id,name,pic,text,date_time) VALUE ("$user_id","$name_","$pic_" ,"$message",NOW())";

$resource = mysqli_query($connect,$query) or die ("error".mysqli_error());

?>