<?php

namespace App\Livewire;

use App\Models\participantes;

class ParticipantesTable extends LivewireTable
{
    protected string $model= participantes::class;
    
    public function columns(){
        return [
            Column::make(__('Name','nombre')),
        ];
    }
}
