<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ubicacion;

class AdminMapa extends Component
{
    public function render()
    {
        $ubicaciones = Ubicacion::latest()->take(100)->get();
        return view('livewire.admin-mapa', compact('ubicaciones'));
    }
}
