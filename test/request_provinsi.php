<?php

require_once "../database/config.php";

$response = json_decode(file_get_contents('https://api.kawalcorona.com/indonesia/provinsi/'));

$provincies = array();

foreach ($response as $provinsi) {
    $newProvinsi = new stdClass();
    
    $newProvinsi->kode_provinsi   = $provinsi->attributes->Kode_Provi;
    $newProvinsi->nama_provinsi   = $provinsi->attributes->Provinsi;
    $newProvinsi->positif         = $provinsi->attributes->Kasus_Posi;
    $newProvinsi->sembuh          = $provinsi->attributes->Kasus_Semb;
    $newProvinsi->meninggal       = $provinsi->attributes->Kasus_Meni;
    $newProvinsi->dalam_perawatan = $newProvinsi->positif - ($newProvinsi->sembuh + $newProvinsi->meninggal);
    
    $provincies[] = $newProvinsi;
}

$queryInsertPrimary = "INSERT INTO pengambilan_provinsi (created_at)
                       VALUES (CURRENT_TIMESTAMP())";

mysqli_query($connection, $queryInsertPrimary);
$inserted_id = mysqli_insert_id($connection);

foreach ($provincies as $provinsi) {
    $queryInsertChild = "INSERT INTO detail_pengambilan_provinsi (
                             id_pengambilan_provinsi,
                             kode_provinsi,
                             nama_provinsi,
                             positif,
                             sembuh,
                             dalam_perawatan,
                             meninggal
                         )
                         VALUES (
                            $inserted_id,
                            $provinsi->kode_provinsi,
                            '$provinsi->nama_provinsi',
                            $provinsi->positif,
                            $provinsi->sembuh,
                            $provinsi->dalam_perawatan,
                            $provinsi->meninggal
                         )";

    mysqli_query($connection, $queryInsertChild);
}