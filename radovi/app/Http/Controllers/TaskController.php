<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskApplication; 
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('nastavnik_id', auth()->id())->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'naziv_rada' => 'required|string|max:255',
            'naziv_rada_en' => 'required|string|max:255',
            'zadatak_rada' => 'required|string',
            'zadatak_rada_en' => 'required|string',
            'tip_studija' => 'required|in:stručni,preddiplomski,diplomski',
        ]);

        $validated['nastavnik_id'] = auth()->id();

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', __('messages.task_created'));
    }

    public function edit(Task $task)
    {
        if ($task->nastavnik_id !== auth()->id()) {
            abort(403);
        }

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->nastavnik_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'naziv_rada' => 'required|string|max:255',
            'naziv_rada_en' => 'required|string|max:255',
            'zadatak_rada' => 'required|string',
            'zadatak_rada_en' => 'required|string',
            'tip_studija' => 'required|in:stručni,preddiplomski,diplomski',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', __('messages.task_updated'));
    }

    public function destroy(Task $task)
    {
        if ($task->nastavnik_id !== auth()->id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', __('messages.task_deleted'));
    }

    public function applications()
    {
        $tasks = Task::where('nastavnik_id', auth()->id())
            ->with(['applications.student'])
            ->get();

        return view('tasks.applications', compact('tasks'));
    }

    public function acceptApplication(TaskApplication $application)
    {
        $task = $application->task;

        if ($task->nastavnik_id !== auth()->id()) {
            abort(403);
        }

        if ($task->accepted_student_id !== null) {
            return back()->with('error', 'This task already has an accepted student.');
        }

        // Accept this application
        $application->update(['status' => 'accepted']);

        // Update task with accepted student
        $task->update(['accepted_student_id' => $application->student_id]);

        // Reject all other applications for this task
        TaskApplication::where('task_id', $task->id)
            ->where('id', '!=', $application->id)
            ->update(['status' => 'rejected']);

        return back()->with('success', __('messages.application_accepted'));
    }

    public function rejectApplication(TaskApplication $application)
    {
        $task = $application->task;
        if ($task->nastavnik_id !== auth()->id()) {
            abort(403);
        }

        $application->update(['status' => 'rejected']);

        return back()->with('success', __('messages.application_rejected'));
    }
}