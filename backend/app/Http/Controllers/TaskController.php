<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        
        if ($projectId) {
            $tasks = Task::where('project_id', $projectId)->with(['assignedTo'])->get();
        } else {
            $tasks = Task::with(['assignedTo', 'project'])->get();
        }
        
        return response()->json($tasks);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'milestone_id' => 'nullable|exists:milestones,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:not_started,in_progress,completed,on_hold',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
        ]);
        
        $task = Task::create($validated);
        
        return response()->json($task, 201);
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::with(['assignedTo', 'project', 'milestone'])->findOrFail($id);
        
        return response()->json($task);
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'milestone_id' => 'nullable|exists:milestones,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'sometimes|required|in:not_started,in_progress,completed,on_hold',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
        ]);
        
        $task->update($validated);
        
        return response()->json($task);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        
        return response()->json(null, 204);
    }
    
    /**
     * Update the status of a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:not_started,in_progress,completed,on_hold',
        ]);
        
        $task->status = $validated['status'];
        $task->save();
        
        return response()->json($task);
    }
    
    /**
     * Get tasks assigned to the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function myTasks()
    {
        $tasks = Task::where('assigned_to', Auth::id())
            ->with(['project'])
            ->orderBy('due_date')
            ->get();
            
        return response()->json($tasks);
    }
}

