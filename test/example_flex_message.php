<?php

$json = file_get_contents('../flex/flex_nasional.json');

$message = json_decode($json);

print_r($message);

require_once "../flex/flex_nasional.php";

print_r(json_encode($flex));
