<?php

require_once '../functions/covid_id.php';
require_once '../functions/covid_prov.php';

date_default_timezone_set('Asia/Jakarta');

/* ---------------------- Data Nasional ---------------------- */

// Ambil data terakhir di tabel nasional
$resultLastDataId    = CovidID\getTodayData();

// Ambil informasi kasus di Indonesia
$dataApiNasional = CovidID\fetchUpdateStatistik();

if (!(array) $resultLastDataId) :
    // Jika data kosong, lakukan insert
    CovidID\insertNewRowToday($dataApiNasional);
elseif (
    $resultLastDataId->positif < $dataApiNasional->positif ||
    $resultLastDataId->sembuh < $dataApiNasional->sembuh ||
    $resultLastDataId->meninggal < $dataApiNasional->meninggal
) :
    // Jika data hari ini sudah usang, update
    CovidID\updateTodayData($resultLastDataId->id, $dataApiNasional);
endif;

/* ---------------------- End of Data Nasional ---------------------- */

/* ---------------------- Data Provinsi ---------------------- */



/* ---------------------- End of Data Provinsi ---------------------- */