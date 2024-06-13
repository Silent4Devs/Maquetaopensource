<?php

namespace App\Http\Livewire;

use GuzzleHttp\Client;
use Livewire\Component;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use GuzzleHttp\Promise;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use stdClass;

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
        $this->dataOperations = $this->makeApiRequestToAllOperations();
        $this->dataAdversaries = $this->makeApiRequestToAllAdversaries();
        $this->dataAgents = $this->makeApiRequestToAllAgents();
        //dd($this->dataOperations);
    }

    public function updatedAtaque($property){
        $dataArray = []; // Array to store all response data
        $client = new Client();
            try {
                $response = $client->get($this->url.'adversaries/'.$property, [
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

    public function render()
    {
        return view('livewire.home');
    }

    public function consumoApiv2(string $index, ?string $parameter = null)
    {
        $client = new Client();
        try {
            $url = $this->url.$index;

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

    public function consumoOperationPost(string $name, string $ataque)
    {
        $uuid = Uuid::uuid4()->toString();

        $postData = [
            "id" => $uuid,
            "name" => $name,
            "adversary" => [
                "adversary_id" => $ataque,
                "name" => "string",
                "description" => "string",
                "atomic_ordering" => ["string"],
                "objective" => "string",
                "tags" => ["string"],
                "plugin" => null
            ],
            "jitter" => "2/8",
            "planner" => [
                "id" => "aaa7c857-37a0-4c4a-85f7-4e9f7f30e31a",
                "name" => "string",
                "module" => "string",
                "params" => new stdClass(),
                "description" => "string",
                "stopping_conditions" => [],
                "ignore_enforcement_modules" => ["string"],
                "allow_repeatable_abilities" => true,
                "plugin" => null
            ],
            "state" => "running",
            "obfuscator" => "plain-text",
            "autonomous" => 1,
            "auto_close" => true,
            "visibility" => 51,
            "objective" => [
                "id" => "string",
                "name" => "string",
                "description" => "string",
                "goals" => [
                    [
                        "target" => "string",
                        "value" => "string",
                        "count" => 0,
                        "operator" => "string"
                    ]
                ]
            ],
            "use_learning_parsers" => true,
            "group" => "",
            "source" => [
                "id" => "d32b9c3-9593-4c33-b0db-e2007315096b",
                "name" => "string",
                "facts" => [
                    [
                        "trait" => "string",
                        "value" => "string",
                        "score" => 0,
                        "source" => "string",
                        "origin_type" => "IMPORTED",
                        "links" => ["string"],
                        "relationships" => ["string"],
                        "limit_count" => 0,
                        "collected_by" => ["string"],
                        "technique_id" => "string"
                    ]
                ],
                "rules" => [
                    [
                        "action" => "ALLOW",
                        "trait" => "file.sensitive.extension",
                        "match" => ".*"
                    ]
                ],
                "adjustments" => [
                    [
                        "ability_id" => "string",
                        "trait" => "string",
                        "value" => "string",
                        "offset" => 0
                    ]
                ],
                "relationships" => [
                    [
                        "source" => [
                            "trait" => "string",
                            "value" => "string",
                            "score" => 0,
                            "source" => "string",
                            "origin_type" => "string",
                            "links" => ["string"],
                            "relationships" => ["string"],
                            "limit_count" => 0,
                            "collected_by" => ["string"],
                            "technique_id" => "string"
                        ],
                        "edge" => "string",
                        "target" => [
                            "trait" => "string",
                            "value" => "string",
                            "score" => 0,
                            "source" => "string",
                            "origin_type" => "string",
                            "links" => ["string"],
                            "relationships" => ["string"],
                            "limit_count" => 0,
                            "collected_by" => ["string"],
                            "technique_id" => "string"
                        ],
                        "score" => 0,
                        "origin" => "string"
                    ]
                ],
                "plugin" => null
            ]
        ];

        $client = new Client();
        try {
            $url = $this->url.'operations';

            $response = $client->post($url, [
                'headers' => [
                    'KEY' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $postData, // Send data as JSON in the request body
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
            $CreacionOp = $this->consumoOperationPost($this->nombre, $this->ataque);
            $this->currentStep = 2;
        }
    }

    public function updatedAtaqueOperations($property){
        $client = new Client();
            try {
                $response = $client->get($this->url.'operations/'.$property, [
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
}
