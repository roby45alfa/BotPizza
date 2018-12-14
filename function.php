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
  if(is_file('pizze.txt')) $contents = file('pizze.txt');
  else{
  $parameters = array('chat_id' => $chatId, "text" => "Nessuna Pizza è stata ordinata" . "\u{1F62A}");
  $parameters["method"] = "sendMessage";
  echo json_encode($parameters);
  }
  
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



