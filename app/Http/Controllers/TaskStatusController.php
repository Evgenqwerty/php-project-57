<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class TaskStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taskStatuses = TaskStatus::paginate();

        return view('task_statuses.index',  compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $taskStatus = new TaskStatus();
        return view('task_statuses.create', compact('taskStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:task_statuses',
        ]);

        $taskStatus = new TaskStatus();
        $taskStatus->fill($data);
        $taskStatus->save();
        flash('Статус успешно создан');

        return redirect()->route('task_statuses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskStatus $taskStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        return view('task_statuses.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        var_dump($taskStatus);
        $data = $request->validate([
            'name' => "required|unique:task_statuses,name,{$taskStatus->id}",
        ]);
        $taskStatus->fill($data);
        $taskStatus->save();
        flash(__('Status successfully changed'));
        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $taskStatus = TaskStatus::find($id);
            $taskStatus->delete();
            flash('Статус успешно удален')->success();
        } catch (QueryException $qe) {
            flash('Не удалось удалить статус')->error();
        }
        return redirect()->route('task_statuses.index');
    }
}
