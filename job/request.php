<?php

require_once '../api/CovidIndonesia.php';

$covidId = new CovidIndonesia();

var_dump($covidId->fetchUpdateStatistik());