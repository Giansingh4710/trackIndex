<?php

function make_backup(){
  $sourceFilePath = 'data.csv';

  $baseFolderPath = "backup_data";
  if (!file_exists($baseFolderPath)) {
    mkdir($baseFolderPath, 0755, true);  // Adjust the permissions as needed
  }
  $backUpFilePath_base = $baseFolderPath . '/data';

  $counter = 1;
  $backUp_file = $backUpFilePath_base . '.csv';
  while (file_exists($backUp_file)) {
    $backUp_file = $backUpFilePath_base . '_' . $counter . '.csv';
    $counter++;
  }

  if (rename($sourceFilePath, $backUp_file)) {
    echo json_encode(['success' => 'Backup file created']);
    return true;
  } 

  http_response_code(500); 
  echo json_encode(['error' => 'Failed to create backup file']);
}

function writeCSV($data){
  try {
    $fp = fopen("data.csv", "w");
    for ($i = 0; $i < count($data); $i++) {
      $time = $data[$i]['timeAdded'];
      $keertani = $data[$i]['keertani'];
      $description = $data[$i]['description'];
      $timeStamp = $data[$i]['timeStamp'];
      $link = $data[$i]['link'];
      $shabadId = $data[$i]['shabadId'];

      $line = array($time, $keertani, $description, $timeStamp, $link, $shabadId);
      fputcsv($fp, $line); # $line is an array of strings (array|string[])
    }
  } catch (Exception $e) {
    echo json_encode(['error' => 'Failed while trying to write to CSV file', 'message' => $e->getMessage()]);
    http_response_code(500);
    exit;
  }
  fclose($fp);
}

$jsonData = file_get_contents("php://input"); // Retrieve the JSON data from the POST request
if (empty($jsonData)) {
  http_response_code(400); // Bad Request
  echo json_encode(['error' => 'No JSON data found']);
  exit;
}

$data = json_decode($jsonData, true);
if ($data === null) {
  http_response_code(400); // Bad Request
  echo json_encode(['error' => 'Invalid JSON data']);
  exit;
}

make_backup();
writeCSV($data);

if (file_exists("data.csv")) {
  echo json_encode(['success' => 'Data successfully saved as CSV']);
} else {
  http_response_code(500); // Internal Server Error
  echo json_encode(['error' => 'Failed to save data as CSV']);
}

?>
