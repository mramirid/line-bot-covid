<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/config.php';

date_default_timezone_set('Asia/Jakarta');

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
    $last_update    = strtotime($resultLastDataId->updated_at);

    $message  = 'Statistik kasus di Indonesia' . PHP_EOL . PHP_EOL;
    $message .= "Total positif: $resultLastDataId->positif (+$selisihPositif)" . PHP_EOL;
    $message .= "Total sembuh: $resultLastDataId->sembuh (+$selisihSembuh)" . PHP_EOL;
    $message .= "Total meninggal: $resultLastDataId->meninggal (+$selisihMeninggal)" . PHP_EOL;
    $message .= "Penambahan per hari ini: $selisihTotal" . PHP_EOL;
    $message .= "Tetap jaga kesehatan dan apabila memungkinkan #DirumahAja" . PHP_EOL . PHP_EOL;
    $message .= "Pembaruan Terakhir Pada " . date('H:i', $last_update);

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

function getMessageKasusByProvince($province) {

}

function getAvailableProvinces() {

}

/**
 * Fungsi ini mengambil data pada tanggal sebelumnya
 * Dieksekusi saat ingin mendapatkan data penambahan jumlah kasus
 */
function getYesterdayData()
{
    global $connection;

    $querySelectLastData = "SELECT 
                                pengambilan_provinsi.id AS id_pengambilan,
                                created_at,
                                updated_at,
                                detail_pengambilan_provinsi.id AS id_detail_pengambilan,
                                kode_provinsi,
                                nama_provinsi,
                                positif,
                                sembuh,
                                dalam_perawatan,
                                meninggal
                            FROM pengambilan_provinsi
                            LEFT JOIN detail_pengambilan_provinsi
                            ON pengambilan_provinsi.id = detail_pengambilan_provinsi.id_pengambilan_provinsi
                            WHERE DATE(pengambilan_provinsi.created_at) = CURDATE()-1";

    return mysqli_query($connection, $querySelectLastData);
}

/* ---------------------- End of Getter data provinsi ---------------------- */