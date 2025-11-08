<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'filter' => "nullable|array"
        ]);
        $filter = [
            'status_id' => null,
            'creator_by_id' => null,
            'assigned_by_id' => null
        ];

        $filterTasks = QueryBuilder::for(Task::class);

        if (!empty($data['filter'])) {
            $filter = $data['filter'];
            foreach ($data['filter'] as $key => $value) {
                if (!is_null($value)) {
                    $filterTasks = $filterTasks->where($key, $value);
                }
            }
        }

        $tasks = $filterTasks->paginate();
        $taskStatuses = new TaskStatus();
        $users = new User();
        return view('tasks.index', compact('tasks', 'taskStatuses', 'users', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::guest()) {
            return abort(403);
        }
        $taskStatuses = new TaskStatus();
        $users = new User();
        $labels = new Label();

        return view('tasks.create', compact('taskStatuses', 'users', 'labels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => "required|unique:tasks",
            'description' => "max:1000",
            'status_id' => "required|integer",
            'assigned_to_id' => "nullable|integer",
            'labels' => "nullable|array"
        ]);

        if (isset($data['assigned_to_id'])) {
            $data['assigned_by_id'] = $data['assigned_to_id'];
            unset($data['assigned_to_id']);
        }

        $task = new Task();
        $task->fill($data);
        $task->creator_by_id = Auth::user()->id;
        $task->save();

        if (isset($data['labels'])) {
            $task->labels()->attach($data['labels']);
        }

        flash(__('controllers.tasks_create'))->success();
        return redirect()->route('tasks.index');
    }

    public function show(Task $task)
    {
        $taskStatus = TaskStatus::findOrFail($task->status_id)->name;

        return view('tasks.show', compact('task', 'taskStatus'));
    }

    public function edit(Task $task)
    {
        if (Auth::guest()) {
            return abort(403);
        }
        $taskStatuses = new TaskStatus();
        $users = new User();
        $labels = new Label();

        return view('tasks.edit', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'name' => "required|unique:tasks,name,{$task->id}",
            'description' => "max:1000",
            'status_id' => "required|string",
            'assigned_by_id' => "nullable|string",
            'labels' => "nullable|array"

        ]);
        $task->fill($data);
        $task->save();
        if (array_key_exists('labels', $data)) {
            $task->labels()->sync($data['labels']);
        } else {
            $task->labels()->sync([]);
        }
        flash(__('controllers.tasks_update'))->success();
        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        if (Auth::guest()) {
            return abort(403);
        }
        if (Auth::id() === $task->creator_by_id) {
            $task->labels()->detach();
            $task->delete();
            flash(__('controllers.tasks_destroy'))->success();
        } else {
            flash(__('controllers.tasks_destroy_failed'))->error();
        }
        return redirect()->route('tasks.index');
    }
}
