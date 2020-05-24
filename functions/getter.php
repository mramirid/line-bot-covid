<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/config.php';

date_default_timezone_set('Asia/Jakarta');

/* ---------------------- Getter data nasional ---------------------- */

/**
 * Mengambil statistik nasional
 */
function getMessageKasusNasional()
{
    global $connection;

    // Data hari ini
    $querySelectLastData = "SELECT * FROM nasional ORDER BY id DESC LIMIT 1";
    $resultQuery         = mysqli_query($connection, $querySelectLastData);
    $resultLastDataId    = (object) mysqli_fetch_assoc($resultQuery);

    // Data kemarin
    $resultYesterdayData = getYesterdayDataNasional();

    // Data kemarin lusa
    $resultTwoDaysAgo    = getTwoDaysAgo();

    // Hitung banyak penambahan kasus positif-sembuh-meninggal dari kemarin
    $selisihPositif        = $resultLastDataId->positif - $resultYesterdayData->positif;
    $selisihSembuh         = $resultLastDataId->sembuh - $resultYesterdayData->sembuh;
    $selisihMeninggal      = $resultLastDataId->meninggal - $resultYesterdayData->meninggal;
    $selisihDalamPerawatan = $resultLastDataId->dalam_perawatan - $resultYesterdayData->dalam_perawatan;

    // Hitung total kasus keseluruhan dari kemarin
    $totalYesterday    = $resultYesterdayData->positif + $resultYesterdayData->sembuh + $resultYesterdayData->meninggal;
    $totalToday        = $resultLastDataId->positif + $resultLastDataId->sembuh + $resultLastDataId->meninggal;
    $selisihTotal      = $totalToday - $totalYesterday;
    $pastData          = $resultTwoDaysAgo->total;
    $presentData       = $resultLastDataId->positif + $resultLastDataId->meninggal;
    $exponentialGrowth = number_format(100*(($presentData-$pastData)/$pastData), 2);
    $last_update       = strtotime($resultLastDataId->updated_at);

    if ($selisihTotal != 0) :
        $message  = 'Statistik kasus di Indonesia' . PHP_EOL . PHP_EOL;
        $message .= "- Positif: $resultLastDataId->positif (+" . abs($selisihPositif) . ")" . PHP_EOL;
        $message .= "- Sembuh: $resultLastDataId->sembuh (+" . abs($selisihSembuh) . ")" . PHP_EOL;
        $message .= "- Meninggal: $resultLastDataId->meninggal (+" . abs($selisihMeninggal) . ")" . PHP_EOL;
        $message .= "- Dalam Perawatan: $resultLastDataId->dalam_perawatan (+" . abs($selisihDalamPerawatan) . ")" . PHP_EOL;
        $message .= "- Pertumbuhan Eksponensial: $exponentialGrowth"."%" . PHP_EOL . PHP_EOL;
    else :
        $message  = 'Statistik kasus di Indonesia' . PHP_EOL . PHP_EOL;
        $message .= "- Positif: $resultLastDataId->positif" . PHP_EOL;
        $message .= "- Sembuh: $resultLastDataId->sembuh" . PHP_EOL;
        $message .= "- Meninggal: $resultLastDataId->meninggal" . PHP_EOL;
        $message .= "- Dalam Perawatan: $resultLastDataId->dalam_perawatan" . PHP_EOL;
        $message .= "- Pertumbuhan Eksponensial: $exponentialGrowth"."%" . PHP_EOL . PHP_EOL;
    endif;
    
    $message .= "Tetap jaga kesehatan dan apabila memungkinkan #DirumahAja" . PHP_EOL . PHP_EOL;
    $message .= "Pembaruan terakhir pada " . date('d/m/Y H:i:s', $last_update);

    return $message;
}

/**
 * Fungsi ini mengambil data pada tanggal sebelumnya
 * Dieksekusi saat ingin mendapatkan data penambahan jumlah kasus
 */
function getYesterdayDataNasional()
{
    global $connection;

    $querySelectYesterdayData = "SELECT * FROM nasional WHERE DATE(created_at) = CURDATE()-1 LIMIT 1";
    $resultQuery = mysqli_query($connection, $querySelectYesterdayData);

    return (object) mysqli_fetch_assoc($resultQuery);
}

/** 
 * Mendapatkan data 2 hari yang lalu
 */
function getTwoDaysAgo()
{
    global $connection;

    $querySelectTwoDaysData   = "SELECT positif+meninggal as total FROM nasional WHERE DATE(created_at) = CURDATE()-1 LIMIT 1";
    $resultQuery                = mysqli_query($connection, $querySelectTwoDaysData);

    return (object) mysqli_fetch_assoc($resultQuery);
}

/* ---------------------- End of Getter data nasional ---------------------- */



/* ---------------------- Getter data provinsi ---------------------- */

/**
 * Pencarian sebuah provinsi berdasarkan kode provinsi untuk
 * mendapatkan statistik kasus dari provinsi yang dicari
 */
function getMessageKasusByProvince($kode_provinsi)
{
    $resultToday     = getTodayDataProvinces();
    $resultYesterday = getYesterdayDataProvinces();

    $message = "";

    while ($provinsi = mysqli_fetch_assoc($resultToday)) {
        $provinsiToday     = (object) $provinsi;
        $provinsiYesterday = (object) mysqli_fetch_assoc($resultYesterday);

        if ($provinsiToday->kode_provinsi == $kode_provinsi) {
            // Hitung banyak penambahan kasus positif-sembuh-meninggal dari kemarin
            $selisihPositif   = $provinsiToday->positif - $provinsiYesterday->positif;
            $selisihSembuh    = $provinsiToday->sembuh - $provinsiYesterday->sembuh;
            $selisihMeninggal = $provinsiToday->meninggal - $provinsiYesterday->meninggal;
            $selisihDalamPerawatan = $provinsiToday->dalam_perawatan - $provinsiYesterday->dalam_perawatan;

            // Hitung total kasus keseluruhan dari kemarin
            $totalYesterday   = $provinsiYesterday->positif + $provinsiYesterday->sembuh + $provinsiYesterday->meninggal;
            $totalToday       = $provinsiToday->positif + $provinsiToday->sembuh + $provinsiToday->meninggal;
            $selisihTotal     = $totalToday - $totalYesterday;

            if ($selisihTotal != 0) :
                $message .= "Statistik kasus di $provinsiToday->nama_provinsi" . PHP_EOL . PHP_EOL;
                $message .= "- Positif: $provinsiToday->positif (+$selisihPositif)" . PHP_EOL;
                $message .= "- Sembuh: $provinsiToday->sembuh (+$selisihSembuh)" . PHP_EOL;
                $message .= "- Meninggal: $provinsiToday->meninggal (+$selisihMeninggal)" . PHP_EOL;
                $message .= "- Dalam perawatan: $provinsiToday->dalam_perawatan (+$selisihDalamPerawatan)" . PHP_EOL . PHP_EOL;
            else :
                $message .= "Statistik kasus di $provinsiToday->nama_provinsi" . PHP_EOL . PHP_EOL;
                $message .= "- Positif: $provinsiToday->positif" . PHP_EOL;
                $message .= "- Sembuh: $provinsiToday->sembuh" . PHP_EOL;
                $message .= "- Meninggal: $provinsiToday->meninggal" . PHP_EOL;
                $message .= "- Dalam perawatan: $provinsiToday->dalam_perawatan" . PHP_EOL . PHP_EOL;
            endif;

            break;
        }
    }

    $last_update = strtotime($provinsiToday->updated_at);

    $message .= "Tetap jaga kesehatan dan apabila memungkinkan #DirumahAja" . PHP_EOL . PHP_EOL;
    $message .= "Pembaruan terakhir hari ini pada pukul " . date('H:i', $last_update);

    return $message;
}

/**
 * Pencarian sebuah provinsi berdasarkan nama provinsi untuk
 * mendapatkan statistik kasus dari provinsi yang dicari
 */
function searchMessageByProvinces($keyword)
{
    $resultToday     = searchTodayDataProvinces($keyword);
    $resultYesterday = searchYesterdayDataProvinces($keyword);

    if (mysqli_num_rows($resultToday) != 0) :
        // Jika provinsi yang dicari ada, maka siapkan response
        $message = "";

        $provinsiToday = (object) mysqli_fetch_assoc($resultToday);
        $provinsiYesterday = (object) mysqli_fetch_assoc($resultYesterday);

        // Hitung banyak penambahan kasus positif-sembuh-meninggal dari kemarin
        $selisihPositif        = $provinsiToday->positif - $provinsiYesterday->positif;
        $selisihSembuh         = $provinsiToday->sembuh - $provinsiYesterday->sembuh;
        $selisihMeninggal      = $provinsiToday->meninggal - $provinsiYesterday->meninggal;
        $selisihDalamPerawatan = $provinsiToday->dalam_perawatan - $provinsiYesterday->dalam_perawatan;

        // Hitung total kasus keseluruhan dari kemarin
        $totalYesterday   = $provinsiYesterday->positif + $provinsiYesterday->sembuh + $provinsiYesterday->meninggal;
        $totalToday       = $provinsiToday->positif + $provinsiToday->sembuh + $provinsiToday->meninggal;
        $selisihTotal     = $totalToday - $totalYesterday;
        $last_update      = strtotime($provinsiToday->updated_at);

        if ($selisihTotal != 0) :
            $message .= "Statistik kasus di $provinsiToday->nama_provinsi" . PHP_EOL . PHP_EOL;
            $message .= "- Positif: $provinsiToday->positif (+" . abs($selisihPositif) . ")" . PHP_EOL;
            $message .= "- Sembuh: $provinsiToday->sembuh (+" . abs($selisihSembuh) . ")" . PHP_EOL;
            $message .= "- Meninggal: $provinsiToday->meninggal (+" . abs($selisihMeninggal) . ")" . PHP_EOL;
            $message .= "- Dalam Perawatan: $provinsiToday->dalam_perawatan (+" . abs($selisihDalamPerawatan) . ")" . PHP_EOL . PHP_EOL;
        else :
            $message .= "Statistik kasus di $provinsiToday->nama_provinsi" . PHP_EOL . PHP_EOL;
            $message .= "- Positif: $provinsiToday->positif" . PHP_EOL;
            $message .= "- Sembuh: $provinsiToday->sembuh" . PHP_EOL;
            $message .= "- Meninggal: $provinsiToday->meninggal" . PHP_EOL;
            $message .= "- Dalam perawatan: $provinsiToday->dalam_perawatan" . PHP_EOL . PHP_EOL;
        endif;

        $message .= "Tetap jaga kesehatan dan apabila memungkinkan #DirumahAja" . PHP_EOL . PHP_EOL;
        $message .= "Pembaruan terakhir pada " . date('d/m/Y H:i:s', $last_update);

        return $message;
    else :
        return false;
    endif;
}

/**
 * Mendapatkan list provinsi yang tersedia di sistem
 */
function getMessageAvailableProvinces()
{
    $resultProvinces = getAvailableProvinces();

    $message  = "List provinsi yang tersedia" . PHP_EOL . PHP_EOL;
    $message .= "- [nama_provinsi] [kode_provinsi]" . PHP_EOL . PHP_EOL;

    while ($provinsi = mysqli_fetch_assoc($resultProvinces)) {
        $message .= "- " . $provinsi['nama_provinsi'] . ' [' . $provinsi['kode_provinsi'] . ']' . PHP_EOL;
    }

    $message .= PHP_EOL . "Gunakan kode provinsi untuk melakukan pencarian provinsi. Cek /help";

    return $message;
}

/**
 * List provinsi beserta statistik tiap provinsi
 */
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
        $message .= "- Total penambahan kasus: +$selisihTotal" . "<br>" . "<br>";
    }

    $last_update = strtotime($provinsiToday->updated_at);

    $message .= "Tetap jaga kesehatan dan apabila memungkinkan #DirumahAja" . "<br>" . "<br>";
    $message .= "Pembaruan terakhir hari ini pada pukul " . date('H:i', $last_update);

    return $message;
}

/**
 * Fungsi ini mengambil data provinsi hari ini berdasarkan nama provinsi 
 */
function searchTodayDataProvinces($keyword)
{
    global $connection;

    $querySelectLastData = "SELECT 
                                pengambilan_provinsi.updated_at,
                                nama_provinsi,
                                positif,
                                sembuh,
                                dalam_perawatan,
                                meninggal
                            FROM pengambilan_provinsi
                            LEFT JOIN detail_pengambilan_provinsi
                            ON pengambilan_provinsi.id = detail_pengambilan_provinsi.id_pengambilan_provinsi
                            WHERE DATE(pengambilan_provinsi.created_at) = CURDATE() AND nama_provinsi LIKE '%$keyword%'";

    return mysqli_query($connection, $querySelectLastData);
}

/**
 * Fungsi ini mengambil data provinsi kemarin berdasarkan nama provinsi 
 */
function searchYesterdayDataProvinces($keyword)
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
                            WHERE DATE(pengambilan_provinsi.created_at) = CURDATE()-1 AND nama_provinsi LIKE '%$keyword%'";

    return mysqli_query($connection, $querySelectLastData);
}

/**
 * Fungsi ini mengambil data terbaru pada hari ini
 */
function getTodayDataProvinces()
{
    global $connection;

    $querySelectLastData = "SELECT 
                                pengambilan_provinsi.updated_at,
                                kode_provinsi,
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
