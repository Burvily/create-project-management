<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    /**
     * Display a listing of the milestones.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        
        if ($projectId) {
            $milestones = Milestone::where('project_id', $projectId)
                ->with(['tasks'])
                ->orderBy('due_date')
                ->get();
        } else {
            $milestones = Milestone::with(['project', 'tasks'])
                ->orderBy('due_date')
                ->get();
        }
        
        return response()->json($milestones);
    }

    /**
     * Store a newly created milestone in storage.
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
            'phase_id' => 'nullable|exists:phases,id',
            'due_date' => 'required|date',
            'status' => 'required|in:not_started,in_progress,completed,on_hold',
        ]);
        
        $milestone = Milestone::create($validated);
        
        return response()->json($milestone, 201);
    }

    /**
     * Display the specified milestone.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $milestone = Milestone::with(['tasks', 'project', 'phase'])->findOrFail($id);
        
        return response()->json($milestone);
    }

    /**
     * Update the specified milestone in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $milestone = Milestone::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'phase_id' => 'nullable|exists:phases,id',
            'due_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:not_started,in_progress,completed,on_hold',
        ]);
        
        $milestone->update($validated);
        
        return response()->json($milestone);
    }

    /**
     * Remove the specified milestone from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $milestone = Milestone::findOrFail($id);
        
        // Check if milestone has tasks
        if ($milestone->tasks()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete milestone with associated tasks'
            ], 400);
        }
        
        $milestone->delete();
        
        return response()->json(null, 204);
    }
    
    /**
     * Update the status of a milestone.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $milestone = Milestone::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:not_started,in_progress,completed,on_hold',
        ]);
        
        $milestone->status = $validated['status'];
        $milestone->save();
        
        return response()->json($milestone);
    }
    
    /**
     * Get upcoming milestones.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming()
    {
        $milestones = Milestone::where('due_date', '>=', now())
            ->where('status', '!=', 'completed')
            ->with(['project'])
            ->orderBy('due_date')
            ->take(5)
            ->get();
            
        return response()->json($milestones);
    }
}

