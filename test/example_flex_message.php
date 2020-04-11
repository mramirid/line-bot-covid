<?php

$json = file_get_contents('../templates/flex_nasional.json');

$message = json_decode($json);

print_r($message);

require_once "../templates/flex_nasional.php";

print_r(json_encode($flex));
