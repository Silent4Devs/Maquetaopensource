<h1>RESULTADOS</h1>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header pb-0 px-2">
                <h6 class="mb-0">Creación de ataques</h6>
                <br>
                <p class="font-weight-bold">
                    Resumen Ejecutivo
                    {{ $nombre}}
                </p>
                <p class="font-weight-bold">
                    {{ $ataque_name}}
                </p>
            </div>
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Información del atacante</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <p class="font-weight-bold"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                        fill="currentColor" class="bi bi-laptop" viewBox="0 0 16 16">
                        <path
                            d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5" />
                    </svg>
                    &nbsp;
                    Máquina atacante
                </p>
                <div wire:loading wire:target="ataque">
                    @include('components.loaders')
                </div>
                @if ($optionSelected)
                <p>Name: {{ $dataGetOperation['name'] }}</p>
                <p class="font-weight-bold"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                        fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                        <path
                            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                        <path
                            d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                    </svg>
                    &nbsp;
                    Descripción del ataque:
                </p>
                <p>{{ $adversarieDescription }}</p>
                <p class="font-weight-bold"> Habilidades:</p>
                <div>
                    @foreach ($dataGetAbilities as $item)
                    <span>{{ $item['name'] }}</span>
                    <ol>
                        <li>{{ $item['tactic'] }}</li>
                        <li>{{ $item['technique_name'] }}</li>
                        <li>{{ $item['description'] }}</li>
                    </ol>
                    @endforeach
                </div>
                @else
                <p>En espera de seleccion</p>
                @endif
            </div>
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Información de la victima</h6>
            </div>
            <div wire:loading wire:target="ataque">
                @include('components.loaders')
            </div>
            <div class="card-body pt-4 p-3">
                <p class="font-weight-bold"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                        fill="currentColor" class="bi bi-laptop" viewBox="0 0 16 16">
                        <path
                            d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5" />
                    </svg>
                    &nbsp;
                    Máquina victima
                </p>
                @if ($optionSelected)
                <p>Name: {{ $dataAgents[0]['paw'] }}</p>
                <p>Ip: {{ $dataAgents[0]['server'] }}</p>
                <p>Exe: {{ $dataAgents[0]['exe_name'] }}</p>
                <p>Plataforma: {{ $dataAgents[0]['platform'] }}</p>
                @else
                <p>En espera de seleccion</p>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card">
            @if($ataque_id != null)
            <div class="card-body">
                @forelse ($data as $key_ataque => $inf_ata)
                <ul>
                    <li>
                        Fase: {{$key_ataque + 1}}
                        <ul>
                            @foreach ($inf_ata as $keyIA => $IA)
                            @if ($keyIA == "agent_metadata" || $keyIA == "operation_metadata" || $keyIA ==
                            "attack_metadata" || $keyIA == "ability_metadata")
                            <li>{{ucfirst($keyIA)}}:</li>
                            <ul>
                                @foreach ($IA as $keySubArray => $subArr)
                                <li>{{ucfirst($keySubArray)}} - {{var_dump($subArr)}}</li>
                                @endforeach
                            </ul>
                            @else
                            <li>{{ucfirst($keyIA)}} - {{var_dump($IA ?? '')}}</li>
                            @endif
                            @endforeach
                        </ul>
                    </li>
                </ul>
                @empty
                <h2>VACIO</h2>
                @endforelse
                {{-- <h1>Data: {{ var_dump($info_ataque) ?? '' }}</h1> --}}
            </div>
            @endif
            <div class="position-relative d-flex justify-content-end">
                <button class="btn btn-primary btn-sm" wire:click="reporte">Continuar</button>
            </div>
        </div>
    </div>
</div>