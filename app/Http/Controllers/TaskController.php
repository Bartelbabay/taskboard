<?php

namespace App\Http\Controllers;

use App\Helpers\GoogleSheetHelper;
use App\Helpers\TelegramHelper;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    var GoogleSheetHelper $googleSheetHelper;
    var TelegramHelper $telegramHelper;

    /**
     * @param GoogleSheetHelper $googleSheetHelper
     * @param TelegramHelper $telegramHelper
     */
    public function __construct(GoogleSheetHelper $googleSheetHelper, TelegramHelper $telegramHelper)
    {
        $this->googleSheetHelper = $googleSheetHelper;
        $this->telegramHelper = $telegramHelper;
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'is_completed' => 'required|boolean',
        ]);

        $task = Task::create($request->all());
        $this->telegramHelper->sendTelegramNotification($task);
        $this->googleSheetHelper->addToGoogleSheet($task);

        return response()->json($task, 201);
    }

    public function toggleComplete(Request $request, $id) {
        $task = Task::findOrFail($id);

        $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        $task->update($request->all());
        $this->telegramHelper->sendTelegramNotification($task, 'Marked as ' . $task->is_completed ? 'completed' : 'uncompleted');
        $this->googleSheetHelper->updateGoogleSheet($task);

        return response()->json($task, 200);
    }

    public function update(Request $request, $id) {
        $task = Task::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'is_completed' => 'required|boolean',
        ]);

        $task->update($request->all());
        $this->telegramHelper->sendTelegramNotification($task, 'Updated');
        $this->googleSheetHelper->updateGoogleSheet($task);

        return response()->json($task, 200);
    }

    public function destroy($id) {
        $task = Task::findOrFail($id);
        Task::destroy($id);
        $this->telegramHelper->sendTelegramNotification($task, 'Deleted');
        $this->googleSheetHelper->deleteFromGoogleSheet($id);
        return response()->json(null, 204);
    }

    public function index(Request $request) {
        $tasks = Task::query();

        if ($request->has('status')) {
            $tasks->where('is_completed', $request->status);
        }

        if ($request->has('title')) {
            $tasks->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('id')) {
            $tasks->where('id', $request->id);
        }

        return response()->json($tasks->get());
    }

    public function get(Request $request) {
        return response()->json(Task::findOrFail($request->id));
    }
}
