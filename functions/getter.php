<?php

require_once '../database/config.php';

/* ---------------------- Getter data nasional ---------------------- */

function getMessageKasusNasional()
{
    global $connection;

    // Data hari ini
    $querySelectLastData = "SELECT * FROM nasional ORDER BY id DESC LIMIT 1";
    $resultQuery         = mysqli_query($connection, $querySelectLastData);
    $resultLastDataId    = (object) mysqli_fetch_assoc($resultQuery);

    // Data kemarin
    $resultYesterdayData = getYesterdayDataNasional();

    // Hitung banyak penambahan kasus
    $selisihPositif   = $resultLastDataId->positif - $resultYesterdayData->positif;
    $selisihSembuh    = $resultLastDataId->sembuh - $resultYesterdayData->sembuh;
    $selisihMeninggal = $resultLastDataId->meninggal - $resultYesterdayData->meninggal;

    $totalYesterday   = $resultYesterdayData->positif + $resultYesterdayData->sembuh + $resultYesterdayData->meninggal;
    $totalToday       = $resultLastDataId->positif + $resultLastDataId->sembuh + $resultLastDataId->meninggal;
    $selisihTotal     = $totalToday - $totalYesterday;

    $message  = 'Statistik kasus di Indonesia' . PHP_EOL . PHP_EOL;
    $message .= "Total positif: $resultLastDataId->positif (+$selisihPositif)" . PHP_EOL;
    $message .= "Total sembuh: $resultLastDataId->sembuh (+$selisihSembuh)" . PHP_EOL;
    $message .= "Total meninggal: $resultLastDataId->meninggal (+$selisihMeninggal)" . PHP_EOL;
    $message .= "Penambahan per hari ini: $selisihTotal";

    return $message;
}

/**
 * Fungsi ini mengambil data pada tanggal sebelumnya
 * Dieksekusi saat ingin mendapatkan data penambahan jumlah kasus
 */
function getYesterdayDataNasional()
{
    global $connection;

    $querySelectYesterdayData   = "SELECT * FROM nasional WHERE DATE(created_at) = CURDATE()-1 LIMIT 1";
    $resultQuery                = mysqli_query($connection, $querySelectYesterdayData);

    return (object) mysqli_fetch_assoc($resultQuery);
}

/* ---------------------- End of Getter data nasional ---------------------- */

/* ---------------------- Getter data provinsi ---------------------- */



/* ---------------------- End of Getter data provinsi ---------------------- */