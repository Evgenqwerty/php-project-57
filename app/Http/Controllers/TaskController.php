<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use App\Http\Requests\TaskRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $data = $request->validate([
            'filter' => "nullable|array",
            'filter.status_id' => 'nullable|exists:task_statuses,id',
            'filter.creator_by_id' => 'nullable|exists:users,id',
            'filter.assigned_to_id' => 'nullable|exists:users,id'
        ]);

        $filter = $data['filter'] ?? [
            'status_id' => null,
            'creator_by_id' => null,
            'assigned_to_id' => null
        ];

        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                'status_id',
                'creator_by_id',
                'assigned_to_id'
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
        $this->authorize('create', Task::class);

        $taskStatuses = new TaskStatus();
        $users = new User();
        $labels = new Label();

        return view('tasks.create', compact('taskStatuses', 'users', 'labels'));
    }

    public function store(TaskRequest $request)
    {
        $data = $request->validated();

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
        $this->authorize('update', $task);

        $taskStatuses = new TaskStatus();
        $users = new User();
        $labels = new Label();

        return view('tasks.edit', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

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
        $this->authorize('delete', $task);

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
