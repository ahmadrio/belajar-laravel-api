<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Task\TaskIndexResource;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = TaskIndexResource::collection(
            Task::query()->with('user')->paginate(5)
        );

        return response()->json([
            'status' => true,
            'message' => 'Berhasil get data tugas',
            'data' => $tasks->items(),
            'meta' => [
                'total' => $tasks->total(),
                'per_page' => $tasks->perPage(),
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'from' => $tasks->firstItem(),
                'to' => $tasks->lastItem(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $extension = $request->file('upload_file')->getClientOriginalExtension();

        if (in_array($extension, ['jpg', 'png', 'jpeg'])) {
            $upload_file = $request->file('upload_file')->store('public/images');
        } else {
            $upload_file = $request->file('upload_file')->store('public/files');
        }

        $task = Task::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'upload_file' => $upload_file,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambahkan tugas',
            'data' => new TaskResource($task),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'upload_file' => 'nullable|file',
        ]);

        $upload_file = $task->upload_file;
        if ($request->hasFile('upload_file')) {
            // hapus dulu file yang ada di database
            \File::delete(storage_path("app/{$upload_file}"));

            // lakukan upload ulang
            $extension = $request->file('upload_file')->getClientOriginalExtension();
            if (in_array($extension, ['jpg', 'png', 'jpeg'])) {
                $upload_file = $request->file('upload_file')->store('public/images');
            } else {
                $upload_file = $request->file('upload_file')->store('public/files');
            }
        }

        $task->update([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'upload_file' => $upload_file,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil mengubah tugas',
            'data' => new TaskResource($task),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        \File::delete(storage_path("app/{$task->upload_file}"));

        $task->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
