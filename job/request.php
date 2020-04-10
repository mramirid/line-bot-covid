<?php

require_once '../api/CovidIndonesia.php';
require_once '../api/CovidInternasional.php';
require_once '../db/config.php';

date_default_timezone_set('Asia/Jakarta');

/* ---------------------- Data Nasional ---------------------- */

// Ambil data terakhir di tabel nasional
$querySelectLastData = "SELECT * FROM nasional WHERE DATE(created_at) = CURDATE() LIMIT 1";
$resultQuery         = mysqli_query($link, $querySelectLastData);
$resultLastDataId    = (object) mysqli_fetch_assoc($resultQuery);

// Ambil informasi kasus di Indonesia
$covidId = new CovidIndonesia();
$dataApiNasional = $covidId->fetchUpdateStatistik();

if (!(array) $resultLastDataId) :
    // Jika data kosong, lakukan insert
    $queryInsertIndonesia = "INSERT INTO nasional (positif, sembuh, meninggal)
                             VALUES ($dataApiNasional->positif, $dataApiNasional->sembuh, $dataApiNasional->meninggal)";
    mysqli_query($link, $queryInsertIndonesia);
else :
    // Jika data hari ini sudah usang, update
    if (
        $resultLastDataId->positif < $dataApiNasional->positif ||
        $resultLastDataId->sembuh < $dataApiNasional->sembuh ||
        $resultLastDataId->meninggal < $dataApiNasional->meninggal
    ) :
        $queryUpdateLastData = "UPDATE nasional
                                SET positif = $dataApiNasional->positif,
                                    sembuh = $dataApiNasional->sembuh,
                                    meninggal = $dataApiNasional->meninggal,
                                    updated_at = CURRENT_TIMESTAMP()
                                WHERE id = $resultLastDataId->id";
        mysqli_query($link, $queryUpdateLastData);
    endif;
endif;

/* ---------------------- End of Data Nasional ---------------------- */

/* ---------------------- Data Internasinal ---------------------- */

// Ambil data terakhir di tabel internasional
$querySelectLastData = "SELECT * FROM internasional WHERE DATE(created_at) = CURDATE() LIMIT 1";
$resultQuery         = mysqli_query($link, $querySelectLastData);
$resultLastDataWorld = (object) mysqli_fetch_assoc($resultQuery);

// Ambil informasi kasus di seluruh dunia
$covidInternational   = new CovidInternasional();
$dataApiInternasional = $covidInternational->fetchUpdateStatistik();

if (!(array) $resultLastDataWorld) :
    // Jika data kosong, lakukan insert
    $queryInsertInternasional = "INSERT INTO internasional (positif, sembuh, meninggal)
                                 VALUES ($dataApiInternasional->positif, $dataApiInternasional->sembuh, $dataApiInternasional->meninggal)";
    mysqli_query($link, $queryInsertInternasional);
else :
    // Jika data hari ini sudah usang, update
    if (
        $resultLastDataWorld->positif < $dataApiInternasional->positif ||
        $resultLastDataWorld->sembuh < $dataApiInternasional->sembuh ||
        $resultLastDataWorld->meninggal < $dataApiInternasional->meninggal
    ) :
        $queryUpdateLastData = "UPDATE internasional
                                SET positif = $dataApiInternasional->positif,
                                    sembuh = $dataApiInternasional->sembuh,
                                    meninggal = $dataApiInternasional->meninggal,
                                    updated_at = CURRENT_TIMESTAMP()
                                WHERE id = $resultLastDataWorld->id";
        mysqli_query($link, $queryUpdateLastData);
    endif;
endif;

/* ---------------------- End of Data Internasional ---------------------- */
