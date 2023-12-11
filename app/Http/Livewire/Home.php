<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Home extends Component
{
    use LivewireAlert;

    public $loader_timer = 0;
    public $ataque = [];
    public $victim = [];
    public $currentStep = 0;

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
