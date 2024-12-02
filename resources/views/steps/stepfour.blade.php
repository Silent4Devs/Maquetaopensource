<h1>RESULTADOS</h1>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header pb-0 px-2">
                <h6 class="mb-0">Creación de ataques</h6>
                <br>
                <p class="font-weight-bold">
                    Resumen Ejecutivo Operación
                </p>
            </div>
            <div class="card-body">
                <div>
                    <h4>Resumen Ejecutivo:</h4>
                    {{$operationData}}
                    {{-- <p>
                        En el siguiente reporte se presentan los resultados obtenidos durante la realización de la
                        operación denominada {{ $operationData["name"]}}, el cual fue llevado a cabo utilizando la
                        técnica
                        clasificada como "tipo de ataque"
                    </p>
                    <ul>
                        <li>Numero de hosts: {{count($operationData["host_group"])}}</li>
                        <li>"Dashboards"</li>
                        <li>Periodo de ejecución: <br>
                            Inicio: {{$operationData["execution_start"]}} <br>
                            Fin: {{$operationData["execution_finish"]}}</li>
                    </ul> --}}
                </div>
                <div>
                    {{-- <h4>Detalles de los hosts</h4>

                    @forelse ($operationData["host_details"] as $keyHost => $host)
                    <ul>
                        <li>Direcciones IP
                            <ul>
                                @foreach ($host["host_ip_addrs"] as $keyIPAd => $IPAD)
                                <li>{{$IPAD}}</li>
                                @endforeach
                            </ul>
                        </li>
                        <li>Usuario: {{$host["user"]}}</li>
                        <li>Plataforma / Sistema Operativo: {{$host["platform_so"]}}</li>
                        <li>HostName: {{$host["hostname"]}}</li>
                        @empty
                        <li>Sin registros</li>
                    </ul>
                    @endforelse --}}
                </div>
                <div>
                    <h4>Comandos o Habilidades Ejecutados</h4>

                    <div>
                        <h5>Ejecución Exitosa</h5>
                        {{-- @foreach ($operationData["successful_commands"] => $stepCommand)
                        {{$stepCommand}} --}}
                        {{-- <ul>
                            <li>
                                <h6>Comando-Habilidad: {{$stepCommand["name"]}}</h6>
                            </li>
                            <ul>
                                <li>Nombre de la habilidad: {{$stepCommand["name"]}}</li>
                                <li>Técnica de acuerdo con el Mitre: {{$stepCommand["technique_name"]}}</li>
                                <li>Descripción de la habilidad: {{$stepCommand["description"]}}</li>
                                <li>Resultado - Salida del comando ejecutado: {{$stepCommand["output"]}}
                                </li>
                            </ul>
                        </ul> --}}
                        {{-- @endforeach --}}
                    </div>
                    {{-- <div>
                        <h5>Ejecución Fallida</h5>
                        @forelse ($operationData["steps"] as $keyFSteps => $stepFCommand)
                        @if ($stepCommand["status"] == 1)
                        <ul>
                            <li>
                                <h6>Comando-Habilidad: {{$keyFSteps}}</h6>
                            </li>
                            <ul>
                                <li>Nombre del comando: {{$stepFCommand["command"]}}</li>
                                <ul>
                                    <li>Razon de Fallo: {{$stepFCommand["output"]["stderr"]}}</li>
                                </ul>
                            </ul>
                        </ul>
                        @endif
                        @empty
                        <h6>Sin registros</h6>
                        @endforelse
                    </div>
                    <div>
                        <h5>Ejecución Omitida</h5>
                        @forelse ($operationData["skipped_abilities"] as $keySkipped => $skipped)
                        @forelse ($skipped as $keySkip => $skipCommand)
                        <ul>
                            <li>
                                <h6>Comando-Habilidad: {{$keySkip}}</h6>
                            </li>
                            @forelse ($skipCommand as $keySCommand => $command)
                            <ul>
                                <li>Nombre:{{$command["ability_name"]}}</li>
                                <ul>
                                    <li>Razon de omisión: {{$command["reason"]}}</li>
                                </ul>
                            </ul>
                            @empty
                            <h6>Sin registrar</h6>
                            @endforelse
                        </ul>
                        @empty
                        <h6>Sin registros</h6>
                        @endforelse

                        @empty
                        <h6>Sin registros</h6>
                        @endforelse
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>