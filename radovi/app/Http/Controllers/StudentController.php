<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskApplication;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $tasks = Task::whereNull('accepted_student_id')->with('nastavnik')->get();
        $appliedTaskIds = TaskApplication::where('student_id', auth()->id())
            ->pluck('task_id')
            ->toArray();

        return view('student.tasks', compact('tasks', 'appliedTaskIds'));
    }

    public function apply(Task $task)
    {
        if ($task->accepted_student_id !== null) {
            return back()->with('error', 'This task is no longer available.');
        }

        $existing = TaskApplication::where('task_id', $task->id)
            ->where('student_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already applied for this task.');
        }

        TaskApplication::create([
            'task_id' => $task->id,
            'student_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return back()->with('success', __('messages.application_submitted'));
    }
}