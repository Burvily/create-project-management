<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(Request $request)
    {
        $query = Project::query();
        
        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Search by title or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $projects = $query->paginate(10);
        
        return response()->json($projects);
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_type' => 'required|string',
            'priority' => 'required|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after:start_date',
            'budget_total' => 'required|numeric|min:0',
            'manager_id' => 'required|exists:users,id',
            'department' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_type' => $request->project_type,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'budget_total' => $request->budget_total,
            'budget_spent' => 0,
            'progress' => 0,
            'status' => 'Not Started',
            'risk_level' => $request->risk_level ?? 'Medium',
            'manager_id' => $request->manager_id,
            'client' => $request->client,
            'department' => $request->department,
        ]);

        // Generate WBS using AI (placeholder for actual implementation)
        $this->generateWorkBreakdownStructure($project);

        return response()->json($project, 201);
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        $project->load(['manager', 'team']);
        
        return response()->json($project);
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|string',
            'progress' => 'sometimes|integer|min:0|max:100',
            'start_date' => 'sometimes|date',
            'due_date' => 'sometimes|date|after:start_date',
            'budget_total' => 'sometimes|numeric|min:0',
            'budget_spent' => 'sometimes|numeric|min:0',
            'risk_level' => 'sometimes|string',
            'manager_id' => 'sometimes|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $project->update($request->all());

        return response()->json($project);
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(null, 204);
    }

    /**
     * Get the work breakdown structure for a project.
     */
    public function getWorkBreakdownStructure(Project $project)
    {
        $project->load(['phases.milestones.tasks']);
        
        return response()->json([
            'project' => $project->only(['id', 'title', 'description', 'progress', 'status']),
            'phases' => $project->phases->map(function ($phase) {
                return [
                    'id' => $phase->id,
                    'name' => $phase->name,
                    'progress' => $phase->progress,
                    'budget' => [
                        'allocated' => $phase->budget_allocated,
                        'spent' => $phase->budget_spent
                    ],
                    'duration' => $phase->start_date->format('M Y') . ' - ' . $phase->end_date->format('M Y'),
                    'status' => $phase->status,
                    'milestones' => $phase->milestones->map(function ($milestone) {
                        return [
                            'id' => $milestone->id,
                            'name' => $milestone->name,
                            'progress' => $milestone->progress,
                            'budget' => [
                                'allocated' => $milestone->budget_allocated,
                                'spent' => $milestone->budget_spent
                            ],
                            'duration' => $milestone->start_date->format('M Y') . ' - ' . $milestone->end_date->format('M Y'),
                            'status' => $milestone->status,
                            'tasks' => $milestone->tasks->map(function ($task) {
                                return [
                                    'id' => $task->id,
                                    'name' => $task->name,
                                    'status' => $task->status
                                ];
                            })
                        ];
                    })
                ];
            })
        ]);
    }

    /**
     * Get budget information for a project.
     */
    public function getBudget(Project $project)
    {
        $project->load(['phases']);
        
        $phaseData = $project->phases->map(function ($phase) {
            return [
                'name' => $phase->name,
                'allocated' => round($phase->budget_allocated / 1000000, 2),
                'spent' => round($phase->budget_spent / 1000000, 2)
            ];
        });
        
        // Calculate budget by category (simplified example)
        $categoryData = [
            ['name' => 'Materials', 'allocated' => 12.5, 'spent' => 3.8],
            ['name' => 'Labor', 'allocated' => 8.2, 'spent' => 1.5],
            ['name' => 'Equipment', 'allocated' => 2.3, 'spent' => 0.4],
            ['name' => 'Permits & Fees', 'allocated' => 0.8, 'spent' => 0.1],
            ['name' => 'Contingency', 'allocated' => 0.7, 'spent' => 0]
        ];
        
        return response()->json([
            'totalBudget' => $project->budget_total,
            'totalSpent' => $project->budget_spent,
            'remainingBudget' => $project->budget_remaining,
            'percentSpent' => $project->budget_percentage,
            'phaseData' => $phaseData,
            'categoryData' => $categoryData
        ]);
    }

    /**
     * Get timeline information for a project.
     */
    public function getTimeline(Project $project)
    {
        $project->load(['phases.milestones']);
        
        $milestones = $project->phases->map(function ($phase) {
            return [
                'phase' => $phase->name,
                'events' => $phase->milestones->map(function ($milestone) {
                    return [
                        'name' => $milestone->name,
                        'date' => $milestone->end_date->format('M d, Y'),
                        'status' => $milestone->status
                    ];
                })
            ];
        });
        
        // Generate months for timeline
        $startDate = $project->start_date;
        $endDate = $project->due_date;
        
        $months = [];
        $current = clone $startDate;
        
        while ($current <= $endDate) {
            $months[] = $current->format('M Y');
            $current->modify('+1 month');
        }
        
        // Generate quarters for timeline
        $quarters = [];
        $current = clone $startDate;
        
        while ($current <= $endDate) {
            $quarter = 'Q' . ceil($current->format('n') / 3) . ' ' . $current->format('Y');
            if (!in_array($quarter, $quarters)) {
                $quarters[] = $quarter;
            }
            $current->modify('+1 month');
        }
        
        return response()->json([
            'milestones' => $milestones,
            'months' => $months,
            'quarters' => $quarters
        ]);
    }

    /**
     * Generate a work breakdown structure for a project using AI.
     * This is a placeholder for the actual AI implementation.
     */
    private function generateWorkBreakdownStructure(Project $project)
    {
        // In a real implementation, this would call an AI service
        // For now, we'll create a simple structure manually
        
        $phases = [
            [
                'name' => 'Phase 1: Site Preparation & Foundation',
                'progress' => 0,
                'status' => 'Not Started',
                'budget_allocated' => 4500000,
                'budget_spent' => 0,
                'start_date' => $project->start_date,
                'end_date' => (clone $project->start_date)->addMonths(3),
                'milestones' => [
                    [
                        'name' => 'Site Clearing & Excavation',
                        'progress' => 0,
                        'status' => 'Not Started',
                        'budget_allocated' => 1200000,
                        'budget_spent' => 0,
                        'tasks' => [
                            ['name' => 'Demolition of existing structures', 'status' => 'Not Started'],
                            ['name' => 'Site clearing and grading', 'status' => 'Not Started'],
                            ['name' => 'Excavation for foundation', 'status' => 'Not Started']
                        ]
                    ],
                    [
                        'name' => 'Foundation Construction',
                        'progress' => 0,
                        'status' => 'Not Started',
                        'budget_allocated' => 3300000,
                        'budget_spent' => 0,
                        'tasks' => [
                            ['name' => 'Pile driving', 'status' => 'Not Started'],
                            ['name' => 'Concrete pouring for foundation', 'status' => 'Not Started'],
                            ['name' => 'Waterproofing', 'status' => 'Not Started'],
                            ['name' => 'Foundation inspection', 'status' => 'Not Started']
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Phase 2: Structural Framework',
                'progress' => 0,
                'status' => 'Not Started',
                'budget_allocated' => 8200000,
                'budget_spent' => 0,
                'start_date' => (clone $project->start_date)->addMonths(3),
                'end_date' => (clone $project->start_date)->addMonths(9),
                'milestones' => [
                    [
                        'name' => 'Steel Structure Assembly',
                        'progress' => 0,
                        'status' => 'Not Started',
                        'budget_allocated' => 5500000,
                        'budget_spent' => 0,
                        'tasks' => [
                            ['name' => 'Steel column installation', 'status' => 'Not Started'],
                            ['name' => 'Beam installation', 'status' => 'Not Started'],
                            ['name' => 'Floor decking', 'status' => 'Not Started'],
                            ['name' => 'Structural inspection', 'status' => 'Not Started']
                        ]
                    ]
                ]
            ]
        ];
        
        foreach ($phases as $phaseData) {
            $milestones = $phaseData['milestones'];
            unset($phaseData['milestones']);
            
            $phaseData['project_id'] = $project->id;
            $phase = Phase::create($phaseData);
            
            foreach ($milestones as $milestoneData) {
                $tasks = $milestoneData['tasks'];
                unset($milestoneData['tasks']);
                
                $milestoneData['project_id'] = $project->id;
                $milestoneData['phase_id'] = $phase->id;
                $milestoneData['start_date'] = $phase->start_date;
                $milestoneData['end_date'] = $phase->end_date;
                
                $milestone = Milestone::create($milestoneData);
                
                foreach ($tasks as $taskData) {
                    $taskData['project_id'] = $project->id;
                    $taskData['phase_id'] = $phase->id;
                    $taskData['milestone_id'] = $milestone->id;
                    $taskData['start_date'] = $milestone->start_date;
                    $taskData['due_date'] = $milestone->end_date;
                    
                    Task::create($taskData);
                }
            }
        }
    }
}