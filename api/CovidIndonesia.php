<?php

require_once "../db/config.php";

class CovidIndonesia
{
    private $endpoint = "https://api.kawalcorona.com/indonesia/";

    public function getStatistikKasusForMessage()
    {
        global $link;

        $querySelectLastData = "SELECT * FROM nasional ORDER BY id DESC LIMIT 1";
        $resultQuery         = mysqli_query($link, $querySelectLastData);
        $resultLastDataId    = (object) mysqli_fetch_assoc($resultQuery);

        $message = 'Statistik kasus di Indonesia' . PHP_EOL . PHP_EOL;
        $message .= 'Total positif: ' . str_replace(',', '', $resultLastDataId->positif) . PHP_EOL;
        $message .= 'Total sembuh: ' . str_replace(',', '', $resultLastDataId->sembuh) . PHP_EOL;
        $message .= 'Total meninggal: ' . str_replace(',', '', $resultLastDataId->meninggal);

        return $message;
    }

    public function fetchUpdateStatistik()
    {
        $response = json_decode(file_get_contents($this->endpoint))[0];

        return (object) [
            'positif'   => (int) str_replace(',', '', $response->positif),
            'sembuh'    => (int) str_replace(',', '', $response->sembuh),
            'meninggal' => (int) str_replace(',', '', $response->meninggal)
        ];
    }
}

