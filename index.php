<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'functions/getter.php';

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

		switch (strtolower($userMessage)) {
			case 'halo':
				$message = "Halo juga";
				break;
			case '/nasional':
				$message = getMessageKasusNasional();
				break;
			case '/provinsi':
				require_once "templates/msg_available_provinces.php";
				$message = "";
				break;
			case 'help':
				$message = '"halo" -> Respon halo' . PHP_EOL;
				$message .= '"nasional" -> Meminta statistik kasus COVID-19 di Indonesia';
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