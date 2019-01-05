<?php

function other ($text, $chatId, $username){
  if (W_R_chatId($chatId)) already_ordered_Send_Message($chatId, $username);
  $text = strtolower($text);
  insertinto_database_pizza($text, $chatId);
  $parameters = array('chat_id' => $chatId, "text" => "Grazie $username"."\u{1F600}");
  $parameters["method"] = "sendMessage";
  $keyboard = ['inline_keyboard' => [[['text' =>  'Cambia Pizza', 'callback_data'=> 'changepizza' ]]]];
  $parameters["reply_markup"] = json_encode($keyboard, true);
  echo json_encode($parameters);
}

function other_change($text, $chatId, $username){
  $text = strtolower($text);
  changePizza($text, $chatId);
  $parameters = array('chat_id' => $chatId, "text" => "Grazie $username"."  \u{1F60E}");
  $parameters["method"] = "sendMessage";
  $keyboard = ['inline_keyboard' => [[['text' =>  'Cambia Pizza', 'callback_data'=> 'changepizza' ]]]];
  $parameters["reply_markup"] = json_encode($keyboard, true);
  echo json_encode($parameters);
  exit;
}

function printPizze($chatId){
  $mysqli = new mysqli('localhost', 'domotica2001', '', 'my_domotica2001');
  $result = $mysqli->query("SELECT `Pizza`,`Num` FROM `Pizze`");
  if(!$result->num_rows){
  $parameters = array('chat_id' => $chatId, "text" => "Nessuna Pizza è stata ordinata" . "\u{1F62A}");
  $parameters["method"] = "sendMessage";
  echo json_encode($parameters);
  }
  $pizze = "";
  while($pizzeArray = $result->fetch_array(MYSQLI_ASSOC)){
    $pizze .=$pizzeArray['Num'] . " " . $pizzeArray['Pizza'] . "\n"; 
  }
  $mysqli->query("TRUNCATE TABLE `Pizze`");
  $mysqli->query("TRUNCATE TABLE `ChatId`");
  $parameters = array('chat_id' => $chatId, "text" => "$pizze");
  $parameters["method"] = "sendMessage";
  echo json_encode($parameters);
}

function W_R_chatId($chatId){
  $mysqli = new mysqli('localhost', 'domotica2001', '', 'my_domotica2001');
  $result = $mysqli->query("SELECT * FROM `ChatId` WHERE `ChatId` = '$chatId'");
  if($result->num_rows > 0) return true;
  $result = $mysqli->query("INSERT INTO `ChatId` (`Id`, `ChatId`) VALUES (0, '$chatId')");
  return false;
  
}

function already_ordered_Send_Message($chatId, $username){
  $parameters = array('chat_id' => $chatId, "text" => "$username, Hai gia ordinato una pizza.\nSe vuoi cambiare la pizza contatta l'admin"."\u{1F643}");
  $parameters["method"] = "sendMessage";
  echo json_encode($parameters);
}

function date_order_pizza($data){
   $giorno = date("w", $data);
   if($giorno == "6") return false;
   else return true;
}


function insertinto_database_pizza($pizza, $chatId){
  $mysqli = new mysqli('localhost', 'domotica2001', '', 'my_domotica2001');
  $result = $mysqli->query("SELECT * FROM `Pizze` WHERE `Pizza` = '$pizza'");
  if($result->num_rows > 0){
     $result = $mysqli->query("UPDATE `Pizze` SET `Num` = (Num+1) WHERE `Pizza` = '$pizza'");
 }
  else{
    $result = $mysqli->query("INSERT INTO `my_domotica2001`.`Pizze` (`Id`, `Pizza`, `Num`) VALUES (NULL, '$pizza', 1)");
  }
  $result = $mysqli->query("SELECT `Id` FROM `Pizze` WHERE `Pizza` = '$pizza'");
  $num = $result->fetch_array(MYSQLI_ASSOC);
  $num = $num["Id"];
  $mysqli->query("UPDATE `ChatId` Set `Id` = $num WHERE `ChatId` = '$chatId'");
}

function changePizza($pizza, $chatId){
  $mysqli = new mysqli('localhost', 'domotica2001', '', 'my_domotica2001');
  $resultChatId = $mysqli->query("SELECT `Id` FROM `ChatId` WHERE `ChatId` = '$chatId'");
  $num = $resultChatId->fetch_array(MYSQLI_ASSOC);
  $num = $num["Id"];

  $resultPizze = $mysqli->query("SELECT `Pizza` FROM `Pizze` WHERE `Id` = '$num'");
  if($resultPizze->num_rows == 1) $mysqli->query("DELETE FROM `Pizze` WHERE `Id` = $num");
  else $mysqli->query("UPDATE `my_domotica2001`.`Pizze` SET `Num` = (Num-1) WHERE `Pizze`.`Id` = '$num'");
  
  insertinto_database_pizza($pizza, $chatId);
}