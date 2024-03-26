<?php

namespace App\Livewire;

use App\Models\Todo;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;
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

        $this->resetPage();
    }

    public function delete($todo){
        try {
            Todo::findOrFail($todo)->delete();
        } catch (Exception $e) {
            session()->flash('error', 'Todo Not Found.');
            return;
        }
        
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
