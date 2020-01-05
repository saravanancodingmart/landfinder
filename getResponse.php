<?php
error_reporting(0);
$keyword = json_decode($_POST['query']);
$lat = json_decode($_POST['current_lat']);
$lng = json_decode($_POST['current_lng']);  

$token_url = "https://outpost.mapmyindia.com/api/security/oauth/token?grant_type=client_credentials";

$access_token="";
$token_type="";

$curl_token = curl_init();
curl_setopt($curl_token, CURLOPT_URL, $token_url);
curl_setopt($curl_token, CURLOPT_POST, 1);
curl_setopt($curl_token, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_token, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl_token, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl_token, CURLOPT_POSTFIELDS,
            "client_id=R1COQ57r0_UddN-80_NLFt89BHEFeX7cTXmwVG2tdiT06OyXbOqgukHFpt3xfxOm-tpo5Y6JKN431cJTLTE6Zw==&client_secret=9K_q_9Q2GHMAbyNdcof10gRcszQGZcpOfzv62cEwJ_Pqpd-_GsP_53Qy-hi6k1x-3RJohmyoMwOOZ0mR9Hh6KYmU695pGdj8");
$result_token = curl_exec($curl_token);
$json = json_decode($result_token, true);
$access_token = $json['access_token'];
$token_type = $json['token_type'];
curl_close($curl_token);

$url="";
if($lat!="" && $lng!="")
{
	$url="https://atlas.mapmyindia.com/api/places/nearby/json?keywords=".str_replace(" ", "%20", str_replace(";", ";", $keyword))."&refLocation=".$lat.",".$lng."";
}
$header = array();
$header[] = 'Content-length: 0';
$header[] = 'Content-type: application/json';
$header[] = 'Authorization: bearer edb5769d-dc87-4045-9ffb-d07dca24dc7a';
$header[] = 'Access-Control-Allow-Origin: *';
$header[] = 'Access-Control-Allow-Methods: *';
$header[] = 'Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curl, CURLOPT_VERBOSE, 1);
curl_setopt($curl, CURLOPT_HEADER, 1);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
echo($curl);
$result = curl_exec($curl);
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
$response_header[] =explode("\r\n", substr($result, 0, $header_size));
$body[] = substr($result, $header_size);

curl_close($curl);


if($http_status=='200')
{
	$res['status']='success';
    $res['data']=$body;
    echo json_encode($res);
}
elseif($http_status=='400'){
    
    $res['status']='fail';
    $res['data']="No result found";
    echo json_encode($res);
}
else{

	$res['status']='fail';
    $res['data']=str_replace("message:", "", $response_header[0][6]);
    echo json_encode($res);
}


?>
