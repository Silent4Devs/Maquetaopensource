<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use GuzzleHttp\Client;
class Home extends Component
{
    use LivewireAlert;

    public $loader_timer = 0;
    public $ataque = [];
    public $victim = [];
    public $currentStep = 0;
    public $url = 'http://192.168.7.152:8888/api/rest';
    public $apiKey = 'ADMIN123';
    public $dataAgents;

    public function mount()
    {
        $this->makeApiRequestToOperations();
        $this->dataAgents = $this->makeApiRequestToAllAgents();
    }

    public function makeApiRequestToOperations()
    {
        $index = 'operations';

        $client = new Client();

        try {
            $response = $client->post($this->url, [
                'headers' => [
                    'KEY' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'index' => $index,
                ],
            ]);

            // You can now handle the API response as needed
            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);
            // Do something with $statusCode and $data
        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection error, server error)
            dd($e->getMessage());
        }
    }

    public function makeApiRequestToAllAgents()
    {
        $index = 'agents';

        $client = new Client();

        try {
            $response = $client->post($this->url, [
                'headers' => [
                    'KEY' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'index' => $index,
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

    public function render()
    {
        return view('livewire.home');
    }

    public function ejecutarataque()
    {
        $this->firstStep();
    }

    public function firstStep()
    {
        $this->loader_timer = 1;
    }
}
