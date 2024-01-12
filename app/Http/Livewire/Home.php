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

    public function mount()
    {
        $this->makeApiRequest();
    }

    public function makeApiRequest()
    {
        $url = 'http://192.168.7.152:8888/api/rest';
        $apiKey = 'ADMIN123';
        $index = 'adversaries';

        $client = new Client();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'KEY' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'index' => $index,
                ],
            ]);

            // You can now handle the API response as needed
            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);
            dd($data);
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
