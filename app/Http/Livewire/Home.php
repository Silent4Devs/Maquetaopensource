<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use GuzzleHttp\Client;

class Home extends Component
{
    use LivewireAlert;

    public $loader_timer = 0;
    public $ataque = '';
    public $victim = [];
    public $currentStep = 0;
    public $url = 'http://192.168.7.152:8888/api/rest';
    public $apiKey = 'ADMIN123';
    public $dataAgents;
    public $dataOperations;
    public $optionSelected = false;
    public $dataGetOperation;
    public $operationID = null;

    public function mount()
    {
        $this->dataOperations = $this->makeApiRequestToAllOperations();
        //dd($this->dataOperations);
    }

    public function updatedAtaque($property){
        $client = new Client();
            try {
                $response = $client->get('http://192.168.7.152:8888/api/v2/operations/'.$property, [
                    'headers' => [
                        'KEY' => $this->apiKey,
                        'Content-Type' => 'application/json',
                    ],
                ]);

                // You can now handle the API response as needed
                $statusCode = $response->getStatusCode();
                $data = json_decode($response->getBody(), true);
                $this->optionSelected = true;
                $this->dataGetOperation = $data;
                $this->operationID = $property;
                //dd($this->dataGetOperation);
                // Do something with $statusCode and $data
            } catch (\Exception $e) {
                // Handle exceptions (e.g., connection error, server error)
                dd($e->getMessage());
            }
    }

    public function makeApiRequestToAllOperations()
    {
        $index = 'operations';

        return $this->consumoApi($index);
    }

    public function makeApiRequestToAllAgents()
    {
        $index = 'agents';

        return $this->consumoApi($index);
    }

    public function render()
    {
        return view('livewire.home');
    }

    public function consumoApi(string $index){
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

    public function ejecutarAtaque(){
        if(is_null($this->operationID)){
            $this->alert('warning', 'Seleccione un ataque', [
                'position' => 'center',
                'timer' => 3000,
                'toast' => true,
                'timerProgressBar' => true,
                'showConfirmButton' => true,
                'onConfirmed' => '',
                ]);
        }else{
            dd($this->operationID);
        }
    }

}
