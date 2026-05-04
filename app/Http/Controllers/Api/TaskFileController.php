<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskFileController extends Controller
{
    /**
     * POST /api/tasks/{task}/files
     * Upload a file to a task.
     */
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:10240',
                'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip,rar,csv',
            ],
        ]);

        $file         = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $mimeType     = $file->getMimeType();
        $size         = $file->getSize();

        $path = $file->store("task_files/{$task->id}", 'local');

        $taskFile = TaskFile::create([
            'task_id'       => $task->id,
            'file_path'     => $path,
            'original_name' => $originalName,
            'mime_type'     => $mimeType,
            'size'          => $size,
            'uploaded_by'   => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully.',
            'data'    => $taskFile,
        ], 201);
    }

    /**
     * GET /api/tasks/{task}/files/{file}
     * Download a file.
     */
    public function download(Task $task, TaskFile $file)
    {
        abort_if($file->task_id !== $task->id, 404, 'File not found.');

        if (!Storage::disk('local')->exists($file->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on server.',
            ], 404);
        }

        return Storage::disk('local')->download($file->file_path, $file->original_name);
    }

    /**
     * DELETE /api/tasks/{task}/files/{file}
     * Delete a file.
     */
    public function destroy(Task $task, TaskFile $file)
    {
        abort_if($file->task_id !== $task->id, 404, 'File not found.');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role !== 'admin' && $file->uploaded_by !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.',
            ], 403);
        }

        if (Storage::disk('local')->exists($file->file_path)) {
            Storage::disk('local')->delete($file->file_path);
        }

        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully.',
        ]);
    }
}
