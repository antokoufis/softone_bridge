<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "{########################}",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n    \"service\": \"SqlData\",\n    \"clientID\": \"{########################}\",\n    \"appId\": \"20010\",\n    \"SqlName\": \"fetchDrastikesOusies\"\n}",
  CURLOPT_HTTPHEADER => array(
    "Accept: */*",
    "Accept-Encoding: gzip, deflate",
    "Cache-Control: no-cache",
    "Connection: keep-alive",
    "Content-Type: application/json charset=utf-8",
    "Host: fiva.oncloud.gr",
    "Postman-Token: {########################}",
    "User-Agent: PostmanRuntime/7.17.1",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);

$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
    $result = iconv("Windows-1253", "UTF-8", $response);
    file_put_contents('fetchSyndiazomenaEidi.json', $result);
    echo "File: updated";
     $myfile = fopen("logs.txt", "a") or die("Unable to open file!");
	$txt = date("Y-m-d h:i:sa")." Fetch SyndiazomenaEidi Json file Updated";
	fwrite($myfile, "\n". $txt);
	fclose($myfile);
}