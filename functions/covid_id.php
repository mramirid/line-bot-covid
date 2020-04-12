<?php

namespace CovidID;

require_once "../database/config.php";

function getStatistikKasusForMessage()
{
    global $connection;

    $querySelectLastData = "SELECT * FROM nasional ORDER BY id DESC LIMIT 1";
    $resultQuery         = mysqli_query($connection, $querySelectLastData);
    $resultLastDataId    = (object) mysqli_fetch_assoc($resultQuery);

    $message = 'Statistik kasus di Indonesia' . PHP_EOL . PHP_EOL;
    $message .= 'Total positif: ' . str_replace(',', '', $resultLastDataId->positif) . PHP_EOL;
    $message .= 'Total sembuh: ' . str_replace(',', '', $resultLastDataId->sembuh) . PHP_EOL;
    $message .= 'Total meninggal: ' . str_replace(',', '', $resultLastDataId->meninggal);

    return $message;
}

/* ------------- Keperluan request Cron Job ------------- */

/**
 * Fungsi ini mengambil data statistik terakhir dari API
 */
function fetchUpdateStatistik()
{
    $response = json_decode(file_get_contents('https://api.kawalcorona.com/indonesia/'))[0];

    return (object) [
        'positif'   => (int) str_replace(',', '', $response->positif),
        'sembuh'    => (int) str_replace(',', '', $response->sembuh),
        'meninggal' => (int) str_replace(',', '', $response->meninggal)
    ];
}

/**
 * Fungsi ini mengambil data statistik terakhir (hari ini) dari database
 */
function getTodayData()
{
    global $connection;

    $querySelectLastData = "SELECT * FROM nasional WHERE DATE(created_at) = CURDATE() LIMIT 1";
    $resultQuery         = mysqli_query($connection, $querySelectLastData);

    return (object) mysqli_fetch_assoc($resultQuery);
}

/**
 * Fungsi ini mengambil data pada tanggal sebelumnya
 * Dieksekusi saat ingin mendapatkan data penambahan jumlah kasus
 */
function getYesterdayData()
{
    global $connection;

    $querySelectYesterdayData   = "SELECT * FROM nasional WHERE DATE(created_at) = CURDATE()-1 LIMIT 1";
    $resultQuery                = mysqli_query($connection, $querySelectYesterdayData);

    return (object) mysqli_fetch_assoc($resultQuery);

}

/**
 * Fungsi ini memasukan data baru ke dalam database
 * Dieksekusi ketika hari sudah berganti
 * 
 * Param 1: data terbaru dari API
 */
function insertNewRowToday($dataApiNasional)
{
    global $connection;

    $dalamPerawatan       = $dataApiNasional->positif - ($dataApiNasional->sembuh + $dataApiNasional->meninggal);
    $queryInsertIndonesia = "INSERT INTO nasional (positif, sembuh, meninggal, dalam_perawatan)
                             VALUES ($dataApiNasional->positif, $dataApiNasional->sembuh, $dataApiNasional->meninggal, $dalamPerawatan)";

    mysqli_query($connection, $queryInsertIndonesia);
}

/**
 * Fungsi ini mengecek apakah data yang tersimpan di database sudah usang
 * Dieksekusi ketika terdapat data baru dari API
 */
function isDBExpired($dataDBNasional, $dataApiNasional)
{
    if (
        $dataDBNasional->positif < $dataApiNasional->positif ||
        $dataDBNasional->sembuh < $dataApiNasional->sembuh ||
        $dataDBNasional->meninggal < $dataApiNasional->meninggal
    ) {
        return true;
    }
}

/**
 * Fungsi ini mengupdate data hari ini yang tersimpan di database
 * Dieksekusi ketika ada perubahan dari API
 * 
 * Param 1: id row yang akan diupdate
 * Param 2: data terbaru dari API
 */
function updateTodayData($id, $dataApiNasional)
{
    global $connection;

    $dalamPerawatan      = $dataApiNasional->positif - ($dataApiNasional->sembuh + $dataApiNasional->meninggal);
    $queryUpdateLastData = "UPDATE nasional
                            SET positif         = $dataApiNasional->positif,
                                sembuh          = $dataApiNasional->sembuh,
                                meninggal       = $dataApiNasional->meninggal,
                                dalam_perawatan = $dalamPerawatan,
                                updated_at      = CURRENT_TIMESTAMP()
                            WHERE id = $id";
                            
    mysqli_query($connection, $queryUpdateLastData);
}

/* ------------- End of Keperluan request Cron Job ------------- */