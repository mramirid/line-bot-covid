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

function fetchUpdateStatistik()
{
    $response = json_decode(file_get_contents('https://api.kawalcorona.com/indonesia/'))[0];

    return (object) [
        'positif'   => (int) str_replace(',', '', $response->positif),
        'sembuh'    => (int) str_replace(',', '', $response->sembuh),
        'meninggal' => (int) str_replace(',', '', $response->meninggal)
    ];
}

function getTodayData()
{
    global $connection;

    $querySelectLastData = "SELECT * FROM nasional WHERE DATE(created_at) = CURDATE() LIMIT 1";
    $resultQuery         = mysqli_query($connection, $querySelectLastData);

    return (object) mysqli_fetch_assoc($resultQuery);
}

function insertNewRowToday($dataApiNasional)
{
    global $connection;

    $dalamPerawatan       = $dataApiNasional->positif - ($dataApiNasional->sembuh + $dataApiNasional->meninggal);
    $queryInsertIndonesia = "INSERT INTO nasional (positif, sembuh, meninggal, dalam_perawatan)
                             VALUES ($dataApiNasional->positif, $dataApiNasional->sembuh, $dataApiNasional->meninggal, $dalamPerawatan)";

    mysqli_query($connection, $queryInsertIndonesia);
}

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