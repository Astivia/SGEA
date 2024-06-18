<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

use RamonRietdijk\LivewireTables\Livewire\LivewireTable;

class UsersTable extends LivewireTable
{
    protected string $model=User::class;
    
}
