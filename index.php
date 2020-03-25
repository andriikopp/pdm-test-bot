<?php
class Controller {
	private $token = '1095733659:AAHjMOT6ZO8ZlEWqqwoBbI2-c9yeif5n1Dg';
	private $chat = '238003657';

	function __construct() {
		if (isset($_GET['failure']) && !empty($_GET['failure'])) {
			$inputData = json_decode($_GET['failure']);

			$volt = $inputData->volt;

			$dTreeModel = new DTreeModel();
			$prediction = $dTreeModel->predict($volt);

			$msgText = '❗*УВАГА*❗'
				. "\n" .
				'🔧 ПЕРЕВІРИТИ РОБОТУ ОБЛАДНАННЯ 🔧'
				. "\n\n" .
				'⚠ Ймовірна відмова компоненту:'
				. "\n" .
				'*' . $prediction . '*'
				. "\n\n" .
				'⚡ Напруга на момент відмови:'
				. "\n" .
				'*' . $volt . 'V*';

			$telegramBotSender = new TelegramBotSender();
			$telegramBotSender->sendMessage($this->token, $this->chat, $msgText);

			echo json_encode(array('success' => 'failure at ' . $volt . ' volt noticed'));
		} else {
			echo json_encode(array('error' => 'failure parameter is not set'));
		}
	}
}

class DTreeModel {
	function predict($volt) {
		if ($volt >= 185) {
			return 'comp2';
		}

		return 'comp1';
	}
}

class TelegramBotSender {
	function sendMessage($token, $chat, $msgText) {
		file_get_contents('https://api.telegram.org/bot' . $token . 
			'/sendMessage?chat_id=' . $chat . 
			'&text=' . urlencode($msgText) . 
			'&parse_mode=markdown'
		);
	}
}

$controller = new Controller();
?>