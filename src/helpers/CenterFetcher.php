<?php

use Symfony\Component\HttpClient\HttpClient;

interface DataFetcher
{
    public function fetchDeptData($postalCode);
    public function fetchLocalData($position, $distance);
}

class CenterFetcher implements DataFetcher
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = "https://data.economie.gouv.fr/api/records/1.0/search/?dataset=controle_techn";
    }

    public function removeDuplicates($array): array
    {

        $keyArray = array_map(function ($array) {
            return $array["fields"]["cct_adresse"];
        }, $array);

        $uniqueKeys = array_unique($keyArray);

        $result  = array_values(array_intersect_key($array, $uniqueKeys));

        return $result;
    }

    public function fetchDeptData($postalCode): array
    {
        // Request constants

        $requestData = [
            'debug' => true,
            'query' => [
                'rows' => 30,
                'q' => 'code_postal=' . $postalCode,
                'sort' => 'cct_code_dept',
                'facet' => 'cct_code_dept',
                'facet' => 'code_postal',
                'facet' => 'prix_visite',
                'facet' => 'prix_contre_visite_min',
                'facet' => 'prix_contre_visite_max',
                'facet' => 'coorgeo',
            ],
        ];

        $client = HttpClient::create([
            'max_redirects' => 3,
        ]);
        $response = $client->request(
            'GET',
            $this->baseUrl,
            [
                'query' => $requestData['query']
            ]
        );

        return $response->toArray();
    }

    public function fetchTownData($townName): array
    {
        // Request constants

        $requestData = [
            'debug' => true,
            'query' => [
                'rows' => 100,
                'q' => 'cct_code_commune=' . $townName,
                'sort' => 'cct_code_dept',
                'facet' => 'cct_code_dept',
                'facet' => 'code_postal',
                'facet' => 'prix_visite',
                'facet' => 'prix_contre_visite_min',
                'facet' => 'prix_contre_visite_max',
                'facet' => 'coorgeo',
            ],
        ];

        $client = HttpClient::create([
            'max_redirects' => 3,
        ]);
        $response = $client->request(
            'GET',
            $this->baseUrl,
            [
                'query' => $requestData['query']
            ]
        );

        return $response->toArray();
    }

    public function fetchByIds($idList): array
    {
        // Request constants
        $queryString = "";
        foreach ($idList as $id) {
            if ($queryString === "") {
                $queryString = $queryString . "recordid=" . $id["centerId"];
            } else {
                $queryString = $queryString . " OR recordid=" . $id["centerId"];
            }
        };

        $requestData = [
            'debug' => true,
            'query' => [
                'rows' => 100,
                'q' => $queryString,
                'sort' => 'cct_code_dept',
                'facet' => 'cct_code_dept',
                'facet' => 'code_postal',
                'facet' => 'prix_visite',
                'facet' => 'prix_contre_visite_min',
                'facet' => 'prix_contre_visite_max',
                'facet' => 'coorgeo',
                'facet' => 'cct_tel',
            ],
        ];

        $client = HttpClient::create([
            'max_redirects' => 3,
        ]);
        $response = $client->request(
            'GET',
            $this->baseUrl,
            [
                'query' => $requestData['query']
            ]
        );

        return $response->toArray();
    }

    public function fetchFactory($arg): array
    {

        if (is_numeric($arg)) {
            $response = $this->fetchDeptData($arg);
        } else {
            $response = $this->fetchTownData($arg);
        }

        return $response;
    }

    public function fetchLocalData($position, $distance): array
    {
        return ["centre 1", "centre 2", "centre 3"];
    }
}
