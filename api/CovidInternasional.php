<?php

class CovidInternasional
{
    private $endpoint = "https://api.kawalcorona.com";

    public function fetchUpdateStatistik()
    {
        $responsePositif    = json_decode(file_get_contents($this->endpoint . '/positif'))->value;
        $responseSembuh     = json_decode(file_get_contents($this->endpoint . '/sembuh'))->value;
        $responseMeninggal  = json_decode(file_get_contents($this->endpoint . '/meninggal'))->value;

        return (object) [
            'positif'   => (int) str_replace(',', '', $responsePositif),
            'sembuh'    => (int) str_replace(',', '', $responseSembuh),
            'meninggal' => (int) str_replace(',', '', $responseMeninggal)
        ];
    }
}
