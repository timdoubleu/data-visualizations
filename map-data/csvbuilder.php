<?php

$jsonFolder = "./crawled-data";
$dir = new DirectoryIterator($jsonFolder);
$GLOBALS['csvData'] = [];

function writeCsvFile() {
  $headerRow = [
    'file_src',
    'object_src',
    'id',
    'wmid',
    'name',
    'type',
    'city',
    'state',
    'rating',
    'distance',
    'license_type',
    'online_pickup',
    'delivery',
    'retail_services',
    'slug',
    'wmurl',
  ];

  array_unshift($GLOBALS['csvData'], $headerRow);

  $fp = fopen('./test.csv', 'w');
  foreach ($GLOBALS['csvData'] as $fields) {
    fputcsv($fp, $fields);
  }
  $returnVal = fclose($fp);
  return $returnVal;
}

function parseResponseFile($data, $filename) {
  $dispensaries  = $data->data->regions->dispensary->listings;
  $deliveries    = $data->data->regions->delivery->listings;
  $doctors       = $data->data->regions->doctor->listings;
  $deliverables  = $data->data->regions->deliverable->listings;

  foreach ($dispensaries as $dispensary) {
    $rowData = [
      $filename,
      'dispensaries_obj',
      $dispensary->id,
      $dispensary->wmid,
      $dispensary->name,
      $dispensary->type,
      $dispensary->city,
      $dispensary->state,
      $dispensary->rating,
      $dispensary->distance,
      $dispensary->license_type,
      ($dispensary->online_ordering->enabled_for_pickup ? "Yes" : "No"),
      ($dispensary->online_ordering->enabled_for_delivery ? "Yes" : "No"),
      $retailServices = implode(",", $dispensary->retailer_services),
      $dispensary->slug,
      "https://weedmaps.com/dispensaries/{$dispensary->slug}",
    ];
    array_push($GLOBALS['csvData'], $rowData);
  }
  
  foreach ($deliveries as $delivery) {
    $rowData = [
      $filename,
      'deliveries_obj',
      $delivery->id,
      $delivery->wmid,
      $delivery->name,
      $delivery->type,
      $delivery->city,
      $delivery->state,
      $delivery->rating,
      $delivery->distance,
      $delivery->license_type,
      ($delivery->online_ordering->enabled_for_pickup ? "Yes" : "No"),
      ($delivery->online_ordering->enabled_for_delivery ? "Yes" : "No"),
      $retailServices = implode(",", $delivery->retailer_services),
      $delivery->slug,
      "https://weedmaps.com/deliveries/{$delivery->slug}",
    ];
    array_push($GLOBALS['csvData'], $rowData);
  }

  foreach ($doctors as $doctor) {
    $rowData = [
      $filename,
      'doctors_obj',
      $doctor->id,
      $doctor->wmid,
      $doctor->name,
      $doctor->type,
      $doctor->city,
      $doctor->state,
      $doctor->rating,
      $doctor->distance,
      $doctor->license_type,
      ($doctor->online_ordering->enabled_for_pickup ? "Yes" : "No"),
      ($doctor->online_ordering->enabled_for_delivery ? "Yes" : "No"),
      $retailServices = implode(",", $doctor->retailer_services),
      $doctor->slug,
      "https://weedmaps.com/doctors/{$doctor->slug}",
    ];
    array_push($GLOBALS['csvData'], $rowData);
  }

  foreach ($deliverables as $deliverable) {
    $rowData = [
      $filename,
      'deliverables_obj',
      $deliverable->id,
      $deliverable->wmid,
      $deliverable->name,
      $deliverable->type,
      $deliverable->city,
      $deliverable->state,
      $deliverable->rating,
      $deliverable->distance,
      $deliverable->license_type,
      ($deliverable->online_ordering->enabled_for_pickup ? "Yes" : "No"),
      ($deliverable->online_ordering->enabled_for_delivery ? "Yes" : "No"),
      $retailServices = implode(",", $deliverable->retailer_services),
      $deliverable->slug,
      "https://weedmaps.com/dispensaries/{$deliverable->slug}",
    ];
    array_push($GLOBALS['csvData'], $rowData);
  }
}

foreach ($dir as $fileinfo) {
  if (!$fileinfo->isDot()) {
    $curPath = $fileinfo->getPathname();
    $fileName = $fileinfo->getFilename();
    $jsonArr = json_decode(file_get_contents($curPath));
    echo "Parsing: {$curPath} \n";
    parseResponseFile($jsonArr, $fileName);
  }
}

$written = writeCsvFile();
if ($written) {
  echo "CSV Ready \n";
} else {
  echo "CSV Write Failed \n";
}