<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/getter.php';

use \LINE\LINEBot\SignatureValidator as SignatureValidator;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use \Slim\App;
use \Dotenv\Dotenv;

// load config
$dotenv = new Dotenv(__DIR__);
$dotenv->load();

// initiate app
$configs =  [
	'settings' => ['displayErrorDetails' => true],
];
$app = new App($configs);

/* ROUTES */
$app->get('/', function ($request, $response) {
	return "Lanjutkan!";
});

$app->post('/', function ($request, $response) {
	// get request body and line signature header
	$body 	   = file_get_contents('php://input');
	$signature = $_SERVER['HTTP_X_LINE_SIGNATURE'];

	// log body and signature
	file_put_contents('php://stderr', 'Body: ' . $body);

	// is LINE_SIGNATURE exists in request header?
	if (empty($signature)) {
		return $response->withStatus(400, 'Signature not set');
	}

	// is this request comes from LINE?
	if ($_ENV['PASS_SIGNATURE'] == false && !SignatureValidator::validateSignature($body, $_ENV['CHANNEL_SECRET'], $signature)) {
		return $response->withStatus(400, 'Invalid signature');
	}

	// init bot
	$httpClient = new CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
	$bot = new LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
	$data = json_decode($body, true);
	foreach ($data['events'] as $event) {
		$userMessage = $event['message']['text'];

		// Mendapatkan argumen dari perintah
		$extractCommand = explode(' ', $userMessage);

		// Jika pesan bukan command, abaikan
		if (substr($userMessage, 0, 1) != '/') return;

		switch (strtolower(trim($extractCommand[0]))) {
			case '/halo':
				$message = "Halo juga," . PHP_EOL;
				$message .= "Saya adalah bot yang didesain untuk memberikan info kasus COVID-19 baik secara nasional maupun berdasarkan provinsi." . PHP_EOL;
				$message .= "Ketikkan '/help' untuk mendapatkan informasi mengenai fitur yang diprogram untuk saya";
				break;
			case '/nasional':
				$message = getMessageKasusNasional();
				break;
			case '/provinsi':
				$message = getMessageForKasusProvinsi();
				break;
			case '/available_provinsi':
				$message = getMessageAvailableProvinces();
				break;
			case '/cari_provinsi':
				if (isset($extractCommand[1])) :
					$message = getMessageKasusByProvince($extractCommand[1]);
				else :
					$message = "Masukan kode provinsi yang anda cari";
				endif;
				break;
			case '/cari':
				if (isset($extractCommand[1])) :
					if (sizeof($extractCommand) == 3) :
						array_shift($extractCommand);
						$keyword = trim(implode(" ", $extractCommand));
					elseif (sizeof($extractCommand) == 2) :
						$keyword = $extractCommand[1];
					elseif (sizeof($extractCommand) == 4) :
						array_shift($extractCommand);
						array_shift($extractCommand);
						$keyword = trim(implode(" ", $extractCommand));
					endif;

					if (searchMessageByProvinces($keyword)) :
						$message = searchMessageByProvinces($keyword);
					else :
						$message = "Provinsi tidak ditemukan";
					endif;
				else :
					$message = "Pastikan nama provinsi sudah benar";
				endif;
				break;
			case '/help':
				$message = 'List command:' . PHP_EOL;
				$message .= '1. /halo -> Perkenalan bot' . PHP_EOL;
				$message .= '2. /nasional -> Kasus COVID-19 di Indonesia'  . PHP_EOL;
				$message .= '3. /cari [nama_provinsi] -> Mencari kasus COVID-19 berdasarkan provinsi' . PHP_EOL;
				$message .= 'Misal: /cari jawa timur' . PHP_EOL . PHP_EOL;
				$message .= 'Versi 1.3 - 31/05/2020 22.16 (Search Province improvement)';
				// $message .= '4. /available_provinsi -> List provinsi yang datanya tersedia'  . PHP_EOL;
				// $message .= '5. /cari_provinsi [kode_provinsi] -> Cari provinsi berdasarkan kode provinsi (lihat di /available_provinsi)';
				break;
			default:
				$message = "Maaf perintah tidak diketahui. Ketik '/help' untuk mengetahui fitur";
				break;
		}

		$textMessageBuilder = new TextMessageBuilder($message);
		$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
		return $result->getHTTPStatus() . ' ' . $result->getRawBody();
	}
});

/* JUST RUN IT */
$app->run();
