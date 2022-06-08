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
        $table->foreignId('')->constrained()->onDelete('cascade');
            $table->string('model_type')->nullable();
            $table->string('model_id')->nullable();
            $table->string('title');
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->enum('priority',['Low','Medium','High'])->nullable();
            $table->string('body');
            $table->string('slug');

        Task::create([
            'user_id' => auth()->id(),
            'model_type'
        ]);
    }
}
