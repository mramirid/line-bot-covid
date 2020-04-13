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

    // Hitung banyak penambahan kasus positif-sembuh-meninggal dari kemarin
    $selisihPositif   = $resultLastDataId->positif - $resultYesterdayData->positif;
    $selisihSembuh    = $resultLastDataId->sembuh - $resultYesterdayData->sembuh;
    $selisihMeninggal = $resultLastDataId->meninggal - $resultYesterdayData->meninggal;

    // Hitung total kasus keseluruhan dari kemarin
    $totalYesterday   = $resultYesterdayData->positif + $resultYesterdayData->sembuh + $resultYesterdayData->meninggal;
    $totalToday       = $resultLastDataId->positif + $resultLastDataId->sembuh + $resultLastDataId->meninggal;
    $selisihTotal     = $totalToday - $totalYesterday;

    $message  = 'Statistik kasus di Indonesia' . PHP_EOL . PHP_EOL;
    $message .= "- Total positif: $resultLastDataId->positif (+$selisihPositif)" . PHP_EOL;
    $message .= "- Total sembuh: $resultLastDataId->sembuh (+$selisihSembuh)" . PHP_EOL;
    $message .= "- Total meninggal: $resultLastDataId->meninggal (+$selisihMeninggal)" . PHP_EOL;
    $message .= "- Total penambahan kasus: +$selisihTotal";

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

// Fitur ini tunda dulu
function getMessageKasusByProvince($province) { }

function getMessageAvailableProvinces() { 
    $resultProvinces = getAvailableProvinces();

    $message  = "List provinsi yang tersedia" . PHP_EOL;
    $message .= "- [nama_provinsi] [kode_provinsi]" . PHP_EOL . PHP_EOL;

    while ($provinsi = mysqli_fetch_assoc($resultProvinces)) { 
        $message .= "- " . $provinsi['nama_provinsi'] . ' [' . $provinsi['kode_provinsi'] . ']' . PHP_EOL;
    }

    return $message;
}

function getMessageForKasusProvinsi()
{
    $resultToday     = getTodayDataProvinces();
    $resultYesterday = getYesterdayDataProvinces();

    $message = "";

    while ($provinsi = mysqli_fetch_assoc($resultToday)) {
        $provinsiToday     = (object) $provinsi;
        $provinsiYesterday = (object) mysqli_fetch_assoc($resultYesterday);

        // Hitung banyak penambahan kasus positif-sembuh-meninggal dari kemarin
        $selisihPositif   = $provinsiToday->positif - $provinsiYesterday->positif;
        $selisihSembuh    = $provinsiToday->sembuh - $provinsiYesterday->sembuh;
        $selisihMeninggal = $provinsiToday->meninggal - $provinsiYesterday->meninggal;
        $selisihDalamPerawatan = $provinsiToday->dalam_perawatan - $provinsiYesterday->dalam_perawatan;

        // Hitung total kasus keseluruhan dari kemarin
        $totalYesterday   = $provinsiYesterday->positif + $provinsiYesterday->sembuh + $provinsiYesterday->meninggal;
        $totalToday       = $provinsiToday->positif + $provinsiToday->sembuh + $provinsiToday->meninggal;
        $selisihTotal     = $totalToday - $totalYesterday;

        $message .= "Statistik kasus di $provinsiToday->nama_provinsi" . "<br>" . "<br>";
        $message .= "- Positif: $provinsiToday->positif (+$selisihPositif)" . "<br>";
        $message .= "- Sembuh: $provinsiToday->sembuh (+$selisihSembuh)" . "<br>";
        $message .= "- Meninggal: $provinsiToday->meninggal (+$selisihMeninggal)" . "<br>";
        $message .= "- Dalam perawatan: $provinsiToday->dalam_perawatan (+$selisihDalamPerawatan)" . "<br>";
        $message .= "- Total penambahan kasus: +$selisihTotal" . "<br>" . "<br>" . "<br>";
    }

    return $message;
}

/**
 * Fungsi ini mengambil data terbaru di hari ini
 */
function getTodayDataProvinces()
{
    global $connection;

    $querySelectLastData = "SELECT 
                                nama_provinsi,
                                positif,
                                sembuh,
                                dalam_perawatan,
                                meninggal
                            FROM pengambilan_provinsi
                            LEFT JOIN detail_pengambilan_provinsi
                            ON pengambilan_provinsi.id = detail_pengambilan_provinsi.id_pengambilan_provinsi
                            WHERE DATE(pengambilan_provinsi.created_at) = CURDATE()";

    return mysqli_query($connection, $querySelectLastData);    
}

/**
 * Fungsi ini mengambil data pada tanggal sebelumnya
 * Dieksekusi saat ingin mendapatkan data penambahan jumlah kasus
 */
function getYesterdayDataProvinces()
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

/**
 * Mengambil list apa saja provinsi yang datanya tersedia
 */
function getAvailableProvinces()
{
    global $connection;

    $querySelectLastData = "SELECT nama_provinsi, kode_provinsi
                            FROM pengambilan_provinsi
                            LEFT JOIN detail_pengambilan_provinsi
                            ON pengambilan_provinsi.id = detail_pengambilan_provinsi.id_pengambilan_provinsi
                            WHERE DATE(pengambilan_provinsi.created_at) = CURDATE()";

    return mysqli_query($connection, $querySelectLastData);
}

/* ---------------------- End of Getter data provinsi ---------------------- */