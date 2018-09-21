<?php

/**
* URL-адрес бота и его маркер.
*/

$access_token = '405672024:AAGoESjOOkT8nmS7S3UlJB-H-1_2taAX_P4';

$api = 'https://api.telegram.org/bot' . $access_token;

/**
* Зададим основные переменные.
*/

$output = json_decode(file_get_contents('php://input'), TRUE); // Получим то, что передано скрипту ботом в POST-сообщении и распарсим

$chat_id = $output['message']['chat']['id']; // Выделим идентификатор чата

$first_name = $output['message']['chat']['first_name']; // Выделим имя собеседника

$message = $output['message']['text']; // Выделим сообщение собеседника

$BD = array();
$BD[] = array(
	'lastname' => 'Петриди',
	'name' => "Мария",
	'sername' => 'Николаевна',
	'email' => "maria@iarga.ru",
	'address' => "Калинина 13/47",
	'phone' => "89182192101",
);
$BD[] = array(
	'lastname' => 'Смирнова',
	'name' => "Маргарита",
	'sername' => 'Николаевна',
	'email' => "code7@iarga.ru",
	'address' => "Кубанская 53",
	'phone' => "89385035295",
);

function search_lastname($lname) {
	$ser = array();
	file_put_contents('log.txt', $lname.'\r\n');
	foreach ($BD as $key => $bbd) {
		$pos = strripos($bbd['lastname'], $lname);
		if ($pos !== false) {
			$ser[] = $bbd;
		}
	}
	file_put_contents('log.txt', $ser);
	return $ser;
}
/**
* Получим команды от пользователя.
* Переведём их для удобства в нижний регистр
*/
$replyMarkup = array(
  'keyboard' => array(
      array("Hello", "Exit")
  )
);

$encodedMarkup = json_encode($replyMarkup);

switch(strtolower_ru($message)) {

case ('привет'):

case ('/hello'):

sendMessage($chat_id, 'Привет, '. $first_name . '! ' . $emoji['preload'], $encodedMarkup );

break;

case ('/start'):

sendMessage($chat_id, 'Здравствуйте, '. $first_name . '! ' . 'Введите Фамилию для поиска', $encodedMarkup );

break;

case ('hello'):

sendMessage($chat_id, 'Добрый день, '. $first_name . '! Мы рады что вы с нами! Введите Фамилию для поиска' . $emoji['preload'], $encodedMarkup );

break;

case ('exit'):

sendMessage($chat_id, ''. $first_name . ', этот бот покинуть невозможно' . $emoji['preload'], $encodedMarkup );

break;

default:

$serch = search_lastname($message);
$mess = '';
if (count($serch) > 0) {
	$mess = 'Найдено: \r\n';
	foreach ($serch as $key => $value) {
		$mess .= 'ФИО: '.$value['lastname'] . ' '.$value['name'] . ' '.$value['sername'].'\r\n';
		$mess .= "Email: ".$value['email'].'\r\n';
		$mess .= "Телефон: ".$value['phone'].'\r\n';
		$mess .= "Адрес: ".$value['address'].'\r\n \r\n';
	}
	sendMessage($chat_id, $mess, $encodedMarkup );
} else {
	sendMessage($chat_id, 'Ничего не найдено в нашей базе', $encodedMarkup );
}

sendMessage($chat_id, $message, $encodedMarkup );

break;

}

/**
* Функция отправки сообщения в чат sendMessage().
*/

function sendMessage($chat_id, $message, $reply_markup) {

file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) . "&reply_markup=" . $reply_markup);

}

/**
* Функция перевода символов в нижний регистр, учитывающая кириллицу
*/

function strtolower_ru($text) {

  $alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');

    $alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');

return str_replace($alfavitupper,$alfavitlover,strtolower($text));

}
