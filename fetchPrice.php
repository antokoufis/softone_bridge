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

//Curl results
$response = curl_exec($curl);
$err = curl_error($curl);

//Sting to Json Object
$json = json_decode($response, true);
$rule_id = 1;
$uniques = [];
$rules = [];
$arrayToPush = [];

foreach ($json["rows"] as $value) {
    if (
        !in_array(
            [
                "OmadaPelati" => $value["OmadaPelati"],
                "ItemCode" => $value["ItemCode"],
            ],
            $uniques
        )
    ) {
        $uniques[] = [
            "OmadaPelati" => $value["OmadaPelati"],
            "ItemCode" => $value["ItemCode"],
        ];

        $rules[] = [
            "id" => $rule_id,
            "title" => $value["ItemCode"] . "-" . $value["OmadaPelati"],
            "filters" => [
                "type" => "product_sku",
                "method" => "in_list",
                "value" => $value["ItemCode"],
                "product_variants" => "[]",
            ],
            "conditions" => [
                "type" => "user_role",
                "options" => [
                    "operator" => "in_list",
                    "value" => $value["OmadaPelati"],
                ],
            ],
            "bulk_adjustments" => [
                "operator" => "product",
                "ranges" => [[
                    "from" => $value["FromQty"],
                    "to" => "",
                    "type" => "fixed_price",
                    "value" => $value["Price"],
                    "label" => "SKU"
                ],],
            ],
        ];
        $rule_id++;
    } else {
        $arrayToPush = [
            "from" => $value["FromQty"],
            "to" => "",
            "type" => "fixed_price",
            "value" => $value["Price"],
            "label" => "SKU"
        ];
        $rules[$rule_id - 2]["bulk_adjustments"]["ranges"][] = $arrayToPush;
    }
}
$json = json_encode($rules, JSON_PRETTY_PRINT);
curl_close($curl);


if ($err) {
  echo "cURL Error #:" . $err;
} else {
   // $result = iconv("Windows-1253", "UTF-8", $response);
    file_put_contents('fetchPrice.json', $json);
    echo "File: updated";
     $myfile = fopen("logs.txt", "a") or die("Unable to open file!");
	$txt = date("Y-m-d h:i:sa")." Fetch Price Json file Updated";
	fwrite($myfile, "\n". $txt);
	fclose($myfile);
}


