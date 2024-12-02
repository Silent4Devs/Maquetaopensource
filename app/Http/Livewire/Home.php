<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Livewire\Component;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use GuzzleHttp\Promise;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use stdClass;
use VXM\Async\Async;

class Home extends Component
{
    use LivewireAlert;

    public $operation_uuid = null;
    public $loader_timer = 0;
    public $victim = [];
    public $currentStep = 1;
    public $url = 'host.docker.internal:8888/api/v2/';
    public $apiKey = 'ADMIN123';
    public $dataAgents;
    public $dataOperations;
    public $dataAdversaries;
    public $dataGetAbilities;
    public $optionSelected = false;
    public $dataGetOperation;
    public $operationID = null;
    public $nombre;
    public $ataque;
    public $ataque_id = null;
    public $ataque_name = null;
    // public $info_ataque = null;
    public $orderingAttack = 0;
    public $arrayAttacks = [];
    public $adversarieDescription;

    public $operationData;

    public $data;
    public $lastData;
    public $lastChangeTime;

    public function mount()
    {
        $this->dataOperations = $this->makeApiRequestToAllOperations();
        $this->dataAdversaries = $this->makeApiRequestToAllAdversaries();
        $this->dataAgents = $this->makeApiRequestToAllAgents();

        $this->data = '';
        $this->lastData = $this->data;
        $this->lastChangeTime = Carbon::now();
    }

    public function updatedAtaque($property)
    {
        $dataArray = []; // Array to store all response data
        $client = new Client();
        try {
            $response = $client->get($this->url . 'adversaries/' . $property, [
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
        $oD = null;
        $info = null;
        if ($this->currentStep == 2 && $this->ataque_id != null) {
            // $info = Carbon::now();
            $info = $this->getEventLogById('event-logs', $this->ataque_id);
            $this->data = $info;
            $this->checkForChanges();
            // if (!empty($info)) {
            //     dd($info);
            // }
        }

        return view('livewire.home', ['info_ataque' => $info, 'operationData' => $oD]);
    }

    public function checkForChanges()
    {

        if ($this->data !== $this->lastData) {
            $this->lastData = $this->data;
            $this->lastChangeTime = Carbon::now();
        }

        if (Carbon::now()->diffInMinutes($this->lastChangeTime) >= 2) {
            # code...
            // dd('Alerta Actividad');
            $this->currentStep = 3;
        }
    }

    public function consumoApiv2(string $index, ?string $parameter = null)
    {
        $client = new Client();
        try {
            $url = $this->url . $index;

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

    public function mostrarReporte()
    {
        $this->operationData = $this->reporte();
        dd($this->operationData);
        $this->currentStep = 4;
    }

    public function getReportById(string $index, string $parameter)
    {
        $client = new Client();
        try {
            $url = $this->url . 'operations/' . $parameter . '/' . $index;

            $response = $client->post($url, [
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'KEY' => $this->apiKey,  // Si necesitas agregar una clave de API
                ],
                'json' => [
                    'enable_agent_output' => true, // Los datos que se van a enviar como JSON
                ],
            ]);

            // You can now handle the API response as needed
            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);

            return $data;
            // return $statusCode;

            // Do something with $statusCode and $data
        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection error, server error)
            dd($e->getMessage());
        }
    }

    public function reporte()
    {

        $dataOperation = $this->getReportById('report', $this->operation_uuid);

        $hosts_details = [];

        foreach ($dataOperation["host_group"] as $keyHost => $host) {
            $hosts_details[$keyHost] = [
                "user" => $host["username"],
                "platform_so" => $host["platform"],
                "hostname" => $host["host"],
            ];

            foreach ($host["host_ip_addrs"] as $keyIPAd => $IPAD) {
                $hosts_details[$keyHost]["host_ip_addreses"][$keyIPAd] = $IPAD;
            }
        }

        // dump($hosts_details, $dataOperation);

        $successful_commands = [];

        foreach ($dataOperation["steps"] as $keySteps => $arrayCommand) {

            foreach ($arrayCommand as $stepCommand) {

                foreach ($stepCommand as $command) {
                    // dd($command, $command["status"]);

                    if ($command["status"] == 0) {
                        $successful_commands[] = [
                            "name" => $command["command"],
                            "technique_name" => $command["attack"]["technique_name"],
                            "description" => $command["description"],
                            "output" => $command["output"]["stdout"],
                        ];
                    }
                }
            }
        }

        // dump($successful_commands);

        $failed_commands = [];

        foreach ($dataOperation["steps"] as $keySteps => $arrayCommand) {
            // dump($keySteps, $arrayCommand, $arrayCommand["steps"]);

            foreach ($arrayCommand as $stepCommand) {
                // dump($stepCommand);

                foreach ($stepCommand as $command) {
                    // dd($command["link_id"], $command["status"]);

                    if ($command["status"] == 1) {
                        // dd($command);
                        $failed_commands[] = [
                            "name" => $command["command"],
                            "technique_name" => $command["attack"]["technique_name"],
                            "description" => $command["description"],
                            "output" => $command["output"]["stderr"],
                        ];
                    }
                }
            }
        }

        // dd($failed_commands);
        $skipped_abilities = [];

        foreach ($dataOperation["skipped_abilities"] as $keySkipped => $skipped) {
            foreach ($skipped as $keySkip => $skipCommand) {
                foreach ($skipCommand as $keySCommand => $command) {
                    $skipped_abilities[] = [
                        "namRESULTADOSe" => $command["ability_name"],
                        "skip_reason" => $command["reason"],
                    ];
                }
            }
        }

        $operationData =
            [
                "name" => $dataOperation["name"],
                "hosts_count" => count($dataOperation["host_group"]),
                "execution_start" => \Carbon\Carbon::parse($dataOperation["start"])->format('d/m/Y H:i'),
                "execution_finish" => \Carbon\Carbon::parse($dataOperation["finish"])->format('d/m/Y H:i'),
                "host_details" => $hosts_details,
                "successful_commands" => $successful_commands,
                "failed_commands" => $failed_commands,
                "skipped_abilities" => $skipped_abilities,
            ];

        return $operationData;
    }


    public function consumoOperationPost(string $name, string $ataque)
    {
        $uuid = Uuid::uuid4()->toString();

        $this->operation_uuid = $uuid;

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
            $url = $this->url . 'operations';

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
            $datosProcesados = $this->procesarRecursivamente($data);
            // dd($datosProcesados);
            return $datosProcesados;
            // Do something with $statusCode and $data
        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection error, server error)
            dd($e->getMessage());
        }
    }

    public function procesarRecursivamente($datos)
    {
        return array_map(function ($dato) {

            if (is_array($dato)) {
                return $this->procesarRecursivamente($dato);
            }
            if (is_string($dato)) {
                return preg_replace('/^\w+\(\d+\)\s/', '', $dato);
            }
            return $dato;
        }, $datos);
    }

    public function ejecutarAtaque()
    {
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
            $this->ataque_id = $CreacionOp['id'];
            $this->ataque_name =  $CreacionOp['adversary']["name"];
            // dd($CreacionOp['id']);
            $this->getEventLogById('event-logs', $CreacionOp['id']);
        }
    }

    public function getEventLogById(string $index, string $parameter)
    {
        $client = new Client();
        try {
            $url = $this->url . 'operations/' . $parameter . '/' . $index;

            $response = $client->post($url, [
                'headers' => [
                    'KEY' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]);

            // You can now handle the API response as needed
            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);

            return $data;
            // return $statusCode;

            // Do something with $statusCode and $data
        } catch (\Exception $e) {
            // Handle exceptions (e.g., connection error, server error)
            dd($e->getMessage());
        }
    }

    public function updatedAtaqueOperations($property)
    {
        $client = new Client();
        try {
            $response = $client->get($this->url . 'operations/' . $property, [
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

    public function callAlerta($mensaje)
    {
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
