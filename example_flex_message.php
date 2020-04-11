<?php 

$json = file_get_contents('flex/flex_nasional.json');

$message = json_decode($json);

print_r($message);