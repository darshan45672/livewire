<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;

class TodoList extends Component
{
    // #[Rule('required|min:3|max:50')]
    public $name;
    public $search;
    public $EditTodoID;
    public $EditName;
    public function create(){
        // dd("test");
        $validated = $this->validate([
            'name' => 'required|min:3|max:50'
        ]); 

        Todo::create($validated);

        $this->reset('name');

        session()->flash('success', 'Todo Created Successfully.');
    }

    public function delete($todo){
        Todo::find($todo)->delete();
    }

    public function toggle( $todoId){
        $todo = Todo::find($todoId);
        $todo->completed = !$todo->completed;
        $todo->save();

    }
    public function edit($todoID){      
        $this->EditTodoID = $todoID;
        $this->EditName = Todo::find($todoID)->name;
    }

    public function update(){
        $this->validate([
            'EditName' => 'required|min:3|max:50'
        ]);

        Todo::find($this->EditTodoID)->update([
            'name' => $this->EditName
        ]);

        $this->cancel();
    }
    public function cancel(){
        $this->reset('EditTodoID','EditName');
    }

    public function render()
    {
        return view('livewire.todo-list',[
            // 'todos' => Todo::latest()->get()
            'todos' => Todo::latest()->where('name','like',"%{$this->search}")->paginate(5)
        ]);
    }
}
