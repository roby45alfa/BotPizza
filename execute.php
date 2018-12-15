<?php
require_once "function.php";
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if(!$update) exit;

$message = isset($update['message']) ? $update['message'] : "";
$messageId = isset($message['message_id']) ? $message['message_id'] : "";
$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
$firstname = isset($message['chat']['first_name']) ? $message['chat']['first_name'] : "";
$lastname = isset($message['chat']['last_name']) ? $message['chat']['last_name'] : "";
$username = isset($message['chat']['username']) ? $message['chat']['username'] : "";
$replymessage = isset($message["reply_to_message"]) ? $message["reply_to_message"] : "";
$photo = isset($message['photo']) ? $message['photo'] : "";
$date = isset($message['date']) ? $message['date'] : "";
$text = isset($message['text']) ? $message['text'] : "";
$response = "";
$parameters = "";

if (!date_order_pizza($date)){
  $parameters = array('chat_id' => $chatId, "text" => "Oggi non puoi ordinare le pizze,\nMi spiace"."\u{1F636}");
  $parameters["method"] = "sendMessage";
  echo json_encode($parameters);
};
$text = trim($text);

header("Content-Type: application/json; charset=utf-8");
if($replymessage != "") other($text, $chatId, $username);

switch($text){
  case "/start":
  case "/pizza":
    $response = "Che pizza vuoi?". "   \u{1F60B}";
    $parameters = array('chat_id' => $chatId, "text" => $response);
    $parameters["method"] = "sendMessage";
    $parameters["reply_markup"] = '{ "keyboard": [[" Margherita","Americana","4 Formaggi e Patatine"], ["Americana con Bufala","Diavola"],["Prosciutto","Altro"]], "one_time_keyboard": true}';
    echo json_encode($parameters);
    break;
  
  case "Margherita":
  
  case "Prosciutto":
      
  case "Americana":

  case "4 Formaggi e Patatine":

  case "Americana con Bufala":

  case "Diavola":
    if (W_R_chatId($chatId)) already_ordered_Send_Message($chatId, $username);
    $pizza = $text;
    $pizza = strtolower($pizza);
    $fp = fopen("pizze.txt", "a+");
    fwrite($fp, "$pizza\n");
    fclose($fp);
    $parameters = array('chat_id' => $chatId, "text" => "Grazie $username"."  \u{1F60E}");
    $parameters["method"] = "sendMessage";
    echo json_encode($parameters);
    break;
    
  case "Altro":
    $response = "Inserisci la pizza che vuoi!". "  \u{1F60B}";
    $parameters = array('chat_id' => $chatId, "text" => $response);
    $parameters["method"] = "sendMessage";
    $parameters["reply_markup"] = '{ "force_reply": true}';
    echo json_encode($parameters);
    break;

  case "/takepizze":
    if($username == "Roby45Alfa")
      printPizze($chatId);
    else {
      $parameters = array('chat_id' => $chatId, "text" => "Comando utilizzabile solo dall'Admin" . "\u{1F610}");
      $parameters["method"] = "sendMessage";
      echo json_encode($parameters);
    }
    break;

  default:
    $parameters = array('chat_id' => $chatId, "text" => "Comando non disponibile!!". "\u{1F62B}");
    $parameters["method"] = "sendMessage";
    echo json_encode($parameters);
}








