<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    // Upload file to a task
    public function upload(Request $request, Task $task)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,docx,xlsx|max:5120',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Only admin or assigned user can upload
        if ($user->role !== 'admin' && $task->assigned_to !== $user->id) {
            return redirect()->route('tasks.show', $task->id)
                ->with('error', 'Access denied.');
        }

        // Store the file
        $path = $request->file('file')->store('tasks', 'public');

        // Save record in database
        TaskFile::create([
            'task_id'   => $task->id,
            'file_path' => $path,
        ]);

        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'File uploaded successfully.');
    }

    // Download a file
    public function download(Task $task, TaskFile $file): StreamedResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Only admin or assigned user can download
        if ($user->role !== 'admin' && $task->assigned_to !== $user->id) {
            abort(403, 'Access denied.');
        }

        // Check file belongs to this task
        if ($file->task_id !== $task->id) {
            abort(404, 'File not found.');
        }

        // Check file exists in storage
        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File no longer exists.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->download($file->file_path);
    }
}