<?php
function other ($text, $chatId, $username){
  $altro = strtolower($text);
  $fp = fopen("pizze.txt", "a+");
  fwrite($fp, "$altro\n");
  fclose($fp);
  $parameters = array('chat_id' => $chatId, "text" => "Grazie $username"."\u{1F600}");
  $parameters["method"] = "sendMessage";
  echo json_encode($parameters);
}

function printPizze($chatId){
  $contents = file('pizze.txt');
  $parole = array();
  $stringa =  "";
  foreach ($contents as $value) {
  $value = trim($value);
  $parole[$value]++;
  }

  arsort($parole, SORT_NUMERIC);

  foreach ($parole as $key => $val) {
    $stringa.=  "$val x  $key\n";
  } 
  unlink("pizze.txt");
  $parameters = array('chat_id' => $chatId, "text" => "$stringa");
  $parameters["method"] = "sendMessage";
  echo json_encode($parameters);
}



