<?php

namespace App\Http\Livewire;

use GuzzleHttp\Client;
use Livewire\Component;

class ObjetivosCatalogo extends Component
{
    public $dataAgents;

    public $apiKey = 'ADMIN123';

    public function render()
    {
        $this->dataAgents = $this->makeApiRequestToAllAgents();
        //dd($this->dataAgents);

        return view('livewire.objetivos-catalogo');
    }

    public function makeApiRequestToAllAgents()
    {
        $index = 'agents';

        return $this->consumoApiv2($index);
    }

    public function consumoApiv2(string $index, ?string $parameter = null)
    {
        $client = new Client();
        try {
            $url = 'http://192.168.7.152:8888/api/v2/'.$index;

            // If $parameter is provided, append it to the URL
            if ($parameter !== null) {
                $url .= '/'.$parameter;
            }

            $response = $client->get($url, [
                'headers' => [
                    'KEY' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]);

            // You can now handle the API response as needed
            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);

            return $data;

            // Do something with $statusCode and $data
        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection error, server error)
            dd($e->getMessage());
        }
    }
}
