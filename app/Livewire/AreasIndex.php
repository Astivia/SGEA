<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\areas;

use Livewire\WithPagination;

class AreasIndex extends Component
{
    use WithPagination;

    protected $paginationTeme = "bootstrap";

    public function render()
    {

        $Areas=areas::paginate();
        return view('livewire.areas-index',compact('Areas'));
    }
}
