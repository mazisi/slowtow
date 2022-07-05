<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Tasks/Task');
    }
    public function store(Request $request)
    {
    $request->validate([
        'body'=> 'required|max:100',
        'model_id' => 'required',
         'model_type' => 'required',
         ]);
        $add_task = Task::create([
            'user_id' => auth()->id(),
            'model_type'=> $request->model_type,
            'model_id' => $request->model_id,
            'body' => $request->body
        ]);
        if($add_task){
            return back()->with('success','Task added successfully');
        }
        return back()->with('error','Error adding task!!!');
    }

    public function destroy($id)
    {
       $task = Task::find($id);
       if($task->delete()){
        return back()->with('success','Task deleted successfully.');
        }
        return back()->with('error','Error deleting task!!!');
    }
}
