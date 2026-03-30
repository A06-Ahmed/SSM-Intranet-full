<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\CreateTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    public function index(Request $request)
    {
        $perPage = min((int) $request->query('per_page', 15), 100);
        $tasks = $this->taskService->list($perPage, 'created_at', 'desc');

        return $this->success('Tasks retrieved', [
            'items' => TaskResource::collection($tasks->items()),
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'last_page' => $tasks->lastPage(),
            ],
        ]);
    }

    public function store(CreateTaskRequest $request)
    {
        $task = $this->taskService->create($request->validated());

        return $this->success('Task created successfully', new TaskResource($task), 201);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task = $this->taskService->update($task, $request->validated());

        return $this->success('Task updated successfully', new TaskResource($task));
    }

    public function destroy(Task $task)
    {
        $this->taskService->delete($task);

        return $this->success('Task deleted successfully');
    }
}
