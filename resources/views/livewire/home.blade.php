<div>
    <style>
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .pulse-effect {
            animation: pulse 1s infinite;
            color: blue;
            font-weight: bold;
            !important;
        }
    </style>

    <div class="container-fluid">
        <div class="page-header min-height-200 border-radius-xl mt-4"
            style="background-image: url('../assets/img/banner.png'); background-position-y: 50%;">
            {{--  <span class="mask bg-gradient-primary opacity-6"></span>  --}}
        </div>
        <div class="card card-body mx-4 mt-n6">
            <div class="row gx-4">
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            SIMULADOR DE ATAQUES
                        </h5>
                    </div>
                </div>
                <div class="col-lg-7 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative d-flex justify-content-end">
                        <button class="btn btn-primary btn-sm" wire:click="ejecutarataque" wire:loading.remove>Ejecutar
                            ataque</button>
                        <div wire:loading wire:target="ejecutarataque">
                            <div class="spinner-grow text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow text-secondary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow text-danger" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow text-warning" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow text-info" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow text-dark" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-2">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm text-info pulse-effect">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-1-circle" viewBox="0 0 16 16">
                                    <path
                                        d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M9.283 4.002V12H7.971V5.338h-.065L6.072 6.656V5.385l1.899-1.383z" />
                                </svg>
                                Configuración
                            </div>
                            @if ($currentStep = 1)
                                <div class="col-sm pulse-effect text-dark ">
                                @else
                                    <div class="col-sm">
                            @endif
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-2-circle" viewBox="0 0 16 16">
                                <path
                                    d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M6.646 6.24v.07H5.375v-.064c0-1.213.879-2.402 2.637-2.402 1.582 0 2.613.949 2.613 2.215 0 1.002-.6 1.667-1.287 2.43l-.096.107-1.974 2.22v.077h3.498V12H5.422v-.832l2.97-3.293c.434-.475.903-1.008.903-1.705 0-.744-.557-1.236-1.313-1.236-.843 0-1.336.615-1.336 1.306Z" />
                            </svg>
                            Ataque en progreso
                        </div>
                        @if ($currentStep = 2)
                            <div class="col-sm pulse-effect text-dark ">
                            @else
                                <div class="col-sm">
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-3-circle" viewBox="0 0 16 16">
                            <path
                                d="M7.918 8.414h-.879V7.342h.838c.78 0 1.348-.522 1.342-1.237 0-.709-.563-1.195-1.348-1.195-.79 0-1.312.498-1.348 1.055H5.275c.036-1.137.95-2.115 2.625-2.121 1.594-.012 2.608.885 2.637 2.062.023 1.137-.885 1.776-1.482 1.875v.07c.703.07 1.71.64 1.734 1.917.024 1.459-1.277 2.396-2.93 2.396-1.705 0-2.707-.967-2.754-2.144H6.33c.059.597.68 1.06 1.541 1.066.973.006 1.6-.563 1.588-1.354-.006-.779-.621-1.318-1.541-1.318Z" />
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8" />
                        </svg>
                        Resultado
                    </div>
                    <div class="col-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-4-circle" viewBox="0 0 16 16">
                            <path
                                d="M7.519 5.057c.22-.352.439-.703.657-1.055h1.933v5.332h1.008v1.107H10.11V12H8.85v-1.559H4.978V9.322c.77-1.427 1.656-2.847 2.542-4.265ZM6.225 9.281v.053H8.85V5.063h-.065c-.867 1.33-1.787 2.806-2.56 4.218Z" />
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8" />
                        </svg>
                        Reporte
                    </div>
                </div>
            </div>
            <div class="container mt-2">
                <div class="row">
                    <div class="progress-wrapper">
                        <div class="progress-info">
                            <div class="progress-percentage">
                                <span class="text-sm font-weight-bold">Progreso del ataque:
                                    {{ $loader_timer }}%</span>
                            </div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-gradient-primary" role="progressbar" aria-valuenow="60"
                                aria-valuemin="0" aria-valuemax="100" style="width: {{ $loader_timer }}%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header pb-0 px-2">
                    <h6 class="mb-0">Lista de ataques</h6>
                    <span style="font-size: 14px;">Por favor, seleccione el ataque a ejecutar</span>
                </div>
                <div class="card-body pt-4 p-3">
                    <input type="email" class="form-control form-sm" id="exampleFormControlInput1"
                        placeholder="Buscar ...">
                    <div class="form-group mt-2">
                        @for ($i = 0; $i <= 10; $i++)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="option1" id="option1">
                                <label class="form-check-label" for="option1" wire.model="ataque"
                                    value="i">Option
                                    {{ $i }}</label>
                            </div>
                        @endfor
                    </div>

                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Información del atacante</h6>
                </div>
                <div class="card-body pt-4 p-3">
                    <p class="font-weight-bold"><svg xmlns="http://www.w3.org/2000/svg" width="32"
                            height="32" fill="currentColor" class="bi bi-laptop" viewBox="0 0 16 16">
                            <path
                                d="M13.5 3a.5.5 0 0 1 .5.5V11H2V3.5a.5.5 0 0 1 .5-.5zm-11-1A1.5 1.5 0 0 0 1 3.5V12h14V3.5A1.5 1.5 0 0 0 13.5 2zM0 12.5h16a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 12.5" />
                        </svg>
                        &nbsp;
                        Máquina atacante
                    </p>
                    <p>IP: 192.168.9.100</p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <p class="font-weight-bold"><svg xmlns="http://www.w3.org/2000/svg" width="32"
                            height="32" fill="currentColor" class="bi bi-exclamation-triangle"
                            viewBox="0 0 16 16">
                            <path
                                d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                            <path
                                d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                        </svg>
                        &nbsp;
                        Descripción del ataque
                    </p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header pb-0 px-3">
                    <h6 class="mb-0">Información de la victima</h6>
                </div>
                <div class="card-body pt-4 p-3">
                    {{-- <select class="form-select form-select-lg mb-3" aria-label="Seleccione una victima">
                        <option selected>Seleccione una victima</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select> --}}
                    <p class="font-weight-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                            class="bi bi-person" viewBox="0 0 16 16">
                            <path
                                d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664z" />
                        </svg>
                        &nbsp;
                        Victima
                    </p>
                    <p>IP: {{ $dataAgents[0]['host_ip_addrs'][0] }}</p>
                    <p>Plataforma: {{ $dataAgents[0]["platform"] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
