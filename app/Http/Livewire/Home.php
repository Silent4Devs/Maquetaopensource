<?php

namespace App\Http\Livewire;

use GuzzleHttp\Client;
use Livewire\Component;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use GuzzleHttp\Promise;
use Illuminate\Support\Collection;

class Home extends Component
{
    use LivewireAlert;

    public $loader_timer = 0;
    public $ataque;
    public $victim = [];
    public $currentStep = 0;
    public $url = 'http://192.168.7.152:8888/api/v2/';
    public $apiKey = 'ADMIN123';
    public $dataAgents;
    public $dataOperations;
    public $dataAdversaries;
    public $dataGetAbilities;
    public $optionSelected = false;
    public $dataGetOperation;
    public $operationID = null;
    public $nombre;
    public $orderingAttack = 0;
    public $arrayAttacks = [];
    public $adversarieDescription;

    public function mount()
    {
        //$this->dataOperations = $this->makeApiRequestToAllOperations();
        $this->dataAdversaries = $this->makeApiRequestToAllAdversaries();
        $this->dataAgents = $this->makeApiRequestToAllAgents();
        dd($this->dataAdversaries);
    }

    public function updatedAtaque($property){
        $dataArray = []; // Array to store all response data
        $client = new Client();
            try {
                $response = $client->get('http://192.168.7.152:8888/api/v2/adversaries/'.$property, [
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
                $this->adversarieDescription = $data['description'];
                $this->orderingAttack = count($this->dataGetOperation['atomic_ordering']);

                // Inicializamos el arreglo para almacenar las respuestas
                $respuestas = [];

                // Iteramos el proceso tantas veces como sea necesario
                for ($i = 0; $i < $this->orderingAttack; $i++) {
                    // Suponiendo que $this->dataGetOperation['atomic_ordering'] es un array con datos diferentes en cada iteración

                    // Obtener el valor de 'atomic_ordering' para esta iteración
                    $atomicOrdering = $this->dataGetOperation['atomic_ordering'][$i];

                    // Consumir la API y almacenar la respuesta en el arreglo
                    $data = $this->consumoApiv2('abilities', $atomicOrdering);

                    // Agregar la respuesta al arreglo de respuestas
                    $respuestas[] = $data;
                }

                // Puedes imprimir o hacer lo que quieras con los resultados
                $this->dataGetAbilities = $respuestas;

            } catch (\Exception $e) {
                // Handle exceptions (e.g., connection error, server error)
                dd($e->getMessage());
            }
    }

    public function updatedAtaqueOperations($property){
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
                $this->orderingAttack = count($this->dataGetOperation['atomic_ordering']);
                dd($this->dataGetOperation, $this->orderingAttack);
                // Do something with $statusCode and $data
            } catch (\Exception $e) {
                // Handle exceptions (e.g., connection error, server error)
                dd($e->getMessage());
            }
    }

    public function makeApiRequestToAllOperations()
    {
        $index = 'operations';

        return $this->consumoApiv2($index);
    }

    public function makeApiRequestToAllAgents()
    {
        $index = 'agents';

        return $this->consumoApiv2($index);
    }

    public function makeApiRequestToAllAdversaries()
    {
        $index = 'adversaries';

        return $this->consumoApiv2($index);
    }

    public function render()
    {
        return view('livewire.home');
    }

    public function consumoApiv2(string $index, ?string $parameter = null)
    {
        $client = new Client();
        try {
            $url = 'http://192.168.7.152:8888/api/v2/'.$index;

            // If $parameter is provided, append it to the URL
            if ($parameter !== null) {
                $url .= '/' . $parameter;
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

    public function ejecutarAtaque(){
        $valido = true;

        if (is_null($this->nombre)) {
            $this->callAlerta('Asigne un nombre');
            $valido = false;
        }

        if (empty($this->ataque)) {
            $this->callAlerta('Seleccione un ataque');
            $valido = false;
        }

        if ($valido) {
            dd($this->nombre, $this->ataque);
        }
    }

    public function callAlerta($mensaje){
        $this->alert('warning', $mensaje, [
            'position' => 'center',
            'timer' => 3000,
            'toast' => true,
            'timerProgressBar' => true,
            'showConfirmButton' => true,
            'onConfirmed' => '',
            ]);
    }

    //planner id= aaa7c857-37a0-4c4a-85f7-4e9f7f30e31a
    //source_id =d32b9c3-9593-4c33-b0db-e2007315096b
}
