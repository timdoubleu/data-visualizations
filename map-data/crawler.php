<?php
$mockApi = "./data/mockApiResponse.json";
$geoPath = "./data/ca-geo-data.csv";
$geoVar = array_map('str_getcsv', file($geoPath));
$timerCount = count($geoVar);

function curlGet($url) {
  $curl = curl_init();
  curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url
  ]);
  $result = curl_exec($curl);
  $err = curl_error($curl);
  var_dump($err);
  curl_close($curl);
  return $result;
}

function makeApiRequest($url, $mockApi) {
  echo "Crawling: {$url} \n";
  $json = file_get_contents($mockApi);
  $json = curlGet($url);
  return $json;
}

function checkFilename($filename) {
  $checkedFilename = $filename;
  if(! file_exists($filename)) {
    $checkedFilename = $filename;
  } else {
    $i = 1;
    while(file_exists($checkedFilename)) {           
      $checkedFilename = (string)$filename.$i;
      $i++;
    }
  }
  return $checkedFilename;
}


foreach ($geoVar as $key => $area) {
  if ( $key == 0 ) { continue; }

  $secondsLeft = ($timerCount * 5) - ($key * 5);
  $minsLeft = number_format(($secondsLeft / 60), 2);
  echo "Estimated Time Remaining: {$minsLeft} minutes left. \n";

  $city   = $area[1];
  $state  = $area[2];
  $latS   = $area[3];
  $longS  = $area[4];
  $lat    = number_format(floatval($latS), 2);
  $long   = number_format(floatval($longS), 2);
  $apiUrl = "https://api-g.weedmaps.com/discovery/v1/location?include[]=regions.listings&latlng={$lat},{$long}";
  
  $jsonResponse = makeApiRequest($apiUrl, $mockApi);
  $savedFilename = checkFilename("./crawled-data/response-{$lat},{$long}.json");
  file_put_contents($savedFilename, $jsonResponse);

  $randomSleepCounter = rand(3, 5);
  echo "Sleeping for {$randomSleepCounter} second. \n";
  sleep($randomSleepCounter);
}
