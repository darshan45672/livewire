<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;

class TodoList extends Component
{
    // #[Rule('required|min:3|max:50')]
    public $name;
    public $search;
    public function create(){
        // dd("test");
        $validated = $this->validate([
            'name' => 'required|min:3|max:50'
        ]); 

        Todo::create($validated);

        $this->reset('name');

        session()->flash('success', 'Todo Created Successfully.');
    }
    public function render()
    {
        return view('livewire.todo-list',[
            // 'todos' => Todo::latest()->get()
            'todos' => Todo::latest()->where('name','like',"%{$this->search}")->paginate(5)
        ]);
    }
}
