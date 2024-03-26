<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;

class TodoList extends Component
{
    // #[Rule('required|min:3| max:50')]
    public $name ;
    protected $rules = [
        'name' => 'required|string|max:255|min:3',
    ];
    public function create(){
        // $this->validate();

        Todo::create([$this->validate()]);

        $this->reset('name');

        session()->flash('success', 'Todo Created Successfully');
    }
    public function render()
    {
        return view('livewire.todo-list');
    }
}
