<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskFilterRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controllers\HasMiddleware;

class TaskController extends Controller
{
    use AuthorizesRequests;
    use HasMiddleware;

    public function __construct()
    {
        $this->authorizeResource(Task::class);
    }

    public function index(TaskFilterRequest $request)
    {
        $data = $request->validated();

        $filter = $data['filter'] ?? [
            'status_id' => null,
            'created_by_id' => null,
            'assigned_to_id' => null
        ];

        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id'),
            ])
            ->paginate(15);

        $taskStatuses = TaskStatus::all();
        $users = User::all();

        return view('tasks.index', compact('tasks', 'taskStatuses', 'users', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $taskStatuses = new TaskStatus();
        $users = new User();
        $labels = new Label();

        return view('tasks.create', compact('taskStatuses', 'users', 'labels'));
    }

    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $task = Auth::user()->createdTasks()->create($data);

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
        $taskStatuses = new TaskStatus();
        $users = new User();
        $labels = new Label();

        return view('tasks.edit', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $data = $request->validated();

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
        if (Auth::id() === $task->created_by_id) {
            $task->labels()->detach();
            $task->delete();
            flash(__('controllers.tasks_destroy'))->success();
        } else {
            flash(__('controllers.tasks_destroy_failed'))->error();
        }
        return redirect()->route('tasks.index');
    }
}
