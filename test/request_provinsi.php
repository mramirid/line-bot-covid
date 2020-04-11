<?php

$response = json_decode(file_get_contents('https://api.kawalcorona.com/indonesia/provinsi/'));

print_r($response);
