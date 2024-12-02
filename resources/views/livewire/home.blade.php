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
            {{-- <span class="mask bg-gradient-primary opacity-6"></span> --}}
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
                        <button class="btn btn-primary btn-sm" wire:click="ejecutarAtaque" wire:loading.remove>Ejecutar
                            ataque</button>
                        <div wire:loading wire:target="ejecutarAtaque">
                            @include('components.loaders')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-2">
            @include('progressbar')

            @switch($currentStep)
            @case(1)
            <div class="container-fluid py-4">
                {{-- Step uno --}}
                @include('steps.stepone')
                {{-- Step uno --}}
            </div>
            @break
            @case(2)
            <div class="container-fluid py-4">
                @include('steps.steptwo')
            </div>
            @break
            @case(3)
            <div class="container-fluid py-4">
                @include('steps.stepthree')
            </div>
            @break
            @case(4)
            <div class="container-fluid py-4">
                @include('steps.stepfour')
            </div>
            @break
            @default
            <div class="container-fluid py-4">
                @include('steps.stepfour')
            </div>
            @endswitch
        </div>
    </div>
</div>