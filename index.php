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

$app->post('/', function ($request, $response)
{
	// get request body and line signature header
	$body 	   = file_get_contents('php://input');
	$signature = $_SERVER['HTTP_X_LINE_SIGNATURE'];

	// log body and signature
	file_put_contents('php://stderr', 'Body: '.$body);

	// is LINE_SIGNATURE exists in request header?
	if (empty($signature)){
		return $response->withStatus(400, 'Signature not set');
	}

	// is this request comes from LINE?
	if ($_ENV['PASS_SIGNATURE'] == false && ! SignatureValidator::validateSignature($body, $_ENV['CHANNEL_SECRET'], $signature)) {
		return $response->withStatus(400, 'Invalid signature');
	}

	// init bot
	$httpClient = new CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
	$bot = new LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
	$data = json_decode($body, true);
	foreach ($data['events'] as $event)
	{
		$userMessage = $event['message']['text'];

		// Mendapatkan argumen dari perintah
		$extractCommand = explode(' ', $userMessage);

		switch (strtolower(trim($extractCommand[0]))) {
			case 'halo':
				$message = "Halo juga";
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
				if (isset($extractCommand[1])):
					$message = getMessageKasusByProvince($extractCommand[1]);
				else:
					$message = "Masukan kode provinsi yang anda cari";
				endif;
				break;
			case '/cari':
				if (isset($extractCommand[1])):
					if (sizeof($extractCommand) == 3):
						$hapus = array_shift($extractCommand);
						$keyword = implode(" ", $extractCommand);
					elseif (sizeof($extractCommand) == 4):
						$hapus1 = array_shift($extractCommand);
						$hapus2 = array_shift($extractCommand);
						$keyword = implode(" ", $extractCommand);
					endif;
					$message = searchMessageByProvinces($keyword);
				else:
					$message = "Pastikan nama provinsi sudah benar";
				endif;
				break;
			case '/help':
				$message = '1. halo -> Respon halo' . PHP_EOL;
				$message .= '2. /nasional -> Kasus COVID-19 di Indonesia'  . PHP_EOL;
				$message .= '3. /cari [nama_provinsi] -> Mencari kasus provinsi berdasarkan nama' . PHP_EOL;
				$message .= 'Misal: /cari jawa timur' . PHP_EOL;
				// $message .= '4. /available_provinsi -> List provinsi yang datanya tersedia'  . PHP_EOL;
				// $message .= '5. /cari_provinsi [kode_provinsi] -> Cari provinsi berdasarkan kode provinsi (lihat di /available_provinsi)';
				break;
			case '':
				break;
			default:
				$message = "Maaf perintah tidak diketahui";
				break;
		}

		$textMessageBuilder = new TextMessageBuilder($message);
		$result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
		return $result->getHTTPStatus() . ' ' . $result->getRawBody();
	}
});

/* JUST RUN IT */
$app->run();