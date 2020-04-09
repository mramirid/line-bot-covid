<?php

class CovidIndonesia
{
    private $endpoint = "https://api.kawalcorona.com/indonesia/";

    public function getStatistikKasus()
    {
        $response = json_decode(file_get_contents($this->endpoint))[0];

        $message = 'Statistik kasus di Indonesia' . PHP_EOL . PHP_EOL;
        $message .= 'Total positif: ' . str_replace(',', '', $response->positif) . PHP_EOL;
        $message .= 'Total sembuh: ' . str_replace(',', '', $response->sembuh) . PHP_EOL;
        $message .= 'Total meninggal: ' . str_replace(',', '', $response->meninggal);

        return $message;
    }
}

