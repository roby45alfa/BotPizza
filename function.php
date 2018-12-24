<?php

function other ($text, $chatId, $username){
  if (W_R_chatId($chatId)) already_ordered_Send_Message($chatId, $username);
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
  $mysqli = new mysqli('localhost', 'domotica2001', '', 'my_domotica2001');
  $mysqli->query("ALTER TABLE `Pizze`\n"  . "auto_increment = 1;");
  unlink("pizze.txt");
  unlink("chatId.txt");
  $parameters = array('chat_id' => $chatId, "text" => "$stringa");
  $parameters["method"] = "sendMessage";
  echo json_encode($parameters);
}

function W_R_chatId($chatId){
  $f= fopen("chatId.txt", "a+");
  while (!feof($f)){
    if(fgets($f) == $chatId) return true;
  }
  fwrite($f, "$chatId\n");
  fclose($f);
  return false;
}

function already_ordered_Send_Message($chatId, $username){
  $parameters = array('chat_id' => $chatId, "text" => "$username, Hai gia ordinato una pizza.\nSe vuoi cambiare la pizza contatta l'admin"."\u{1F643}");
  $parameters["method"] = "sendMessage";
  echo json_encode($parameters);
}

function date_order_pizza($data){
   $giorno = date("w", $data);
   if($giorno == "1") return false;
   else return true;
}


  function insertinto_database_pizza($pizza){
    $mysqli = new mysqli('localhost', '', '', '');
    $result = $mysqli->query("SELECT * FROM `Pizze` WHERE `Pizza` = '$pizza'");
    if($result->num_rows > 0){
       $result = $mysqli->query("UPDATE ``.`Pizze` SET `Num` = (Num+1) WHERE `Pizze`.`Pizza` = '$pizza'");
    }
    else{
      $result = $mysqli->query("INSERT INTO ``.`Pizze` (`Id`, `Pizza`, `Num`) VALUES (NULL, '$pizza', '1')");
  }
}
