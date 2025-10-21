<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::paginate();
        $taskStatuses = new TaskStatus();
        $users = new User();
        return view('tasks.index', compact('tasks', 'taskStatuses', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $task = new Task();
        $taskStatuses = new TaskStatus();
        $users = new User();

        return view('tasks.create', compact('task', 'taskStatuses', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:tasks',
            'description' => 'max:1000',
            'status_id' => 'required|string',
            'assigned_by_id' => 'nullable|string'

        ]);
        $task = new Task();
        $task->fill($data);
        $task->creator_by_id = Auth::user()->id;
        $task->save();

        flash('Задача успешно создана');

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $taskStatuses = new TaskStatus();
        $users = new User();

        return view('tasks.edit', compact('task', 'taskStatuses', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|unique:tasks,name,{$task->id}',
            'description' => 'max:1000',
            'status_id' => 'required|string',
            'assigned_by_id' => 'nullable|string'

        ]);
        $task->fill($data);
        $task->save();
        flash(__('Task successfully changed'));
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
