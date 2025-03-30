<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function getStats()
    {
        $totalProjects = Project::count();
        $activeTasks = Task::where('status', 'In Progress')->count();
        
        // Calculate budget utilization
        $totalBudget = Project::sum('budget_total');
        $totalSpent = Project::sum('budget_spent');
        $budgetUtilization = $totalBudget > 0 ? ($totalSpent / $totalBudget) * 100 : 0;
        
        // Calculate average time to completion for active projects
        $activeProjects = Project::where('status', 'In Progress')->get();
        $avgDaysToCompletion = 0;
        
        if ($activeProjects->count() > 0) {
            $totalDays = 0;
            foreach ($activeProjects as $project) {
                $totalDays += $project->due_date->diffInDays(now());
            }
            $avgDaysToCompletion = round($totalDays / $activeProjects->count());
        }
        
        return response()->json([
            'totalProjects' => $totalProjects,
            'activeTasks' => $activeTasks,
            'budgetUtilization' => round($budgetUtilization, 2),
            'timeToCompletion' => $avgDaysToCompletion
        ]);
    }

    /**
     * Get recent activity.
     */
    public function getRecentActivity()
    {
        // Get recent task completions
        $taskCompletions = Task::with(['project', 'assignee'])
            ->where('status', 'Completed')
            ->orderBy('completed_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($task) {
                return [
                    'user' => [
                        'name' => $task->assignee->name,
                        'avatar' => $task->assignee->avatar,
                        'initials' => $this->getInitials($task->assignee->name)
                    ],
                    'action' => 'completed a task',
                    'project' => $task->project->title,
                    'task' => $task->name,
                    'time' => $task->completed_at->diffForHumans(),
                    'icon' => 'CheckCircle2'
                ];
            });
            
        // Get recent milestone updates
        $milestoneUpdates = Milestone::with(['project'])
            ->whereIn('status', ['In Progress', 'Completed'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($milestone) {
                return [
                    'user' => [
                        'name' => $milestone->project->manager->name,
                        'avatar' => $milestone->project->manager->avatar,
                        'initials' => $this->getInitials($milestone->project->manager->name)
                    ],
                    'action' => $milestone->status === 'Completed' ? 'completed a milestone' : 'updated a milestone',
                    'project' => $milestone->project->title,
                    'task' => $milestone->name,
                    'time' => $milestone->updated_at->diffForHumans(),
                    'icon' => $milestone->status === 'Completed' ? 'CheckCircle2' : 'FileText'
                ];
            });
            
        // Merge and sort by time
        $activities = $taskCompletions->concat($milestoneUpdates)
            ->sortByDesc('time')
            ->take(5)
            ->values()
            ->all();
            
        return response()->json($activities);
    }

    /**
     * Get project metrics.
     */
    public function getProjectMetrics()
    {
        $projects = Project::where('status', 'In Progress')
            ->orderBy('progress', 'desc')
            ->take(4)
            ->get()
            ->map(function ($project) {
                return [
                    'name' => $project->title,
                    'progress' => $project->progress,
                    'budget' => [
                        'spent' => $project->budget_spent,
                        'total' => $project->budget_total
                    ],
                    'status' => $this->getProjectStatus($project)
                ];
            });
            
        return response()->json($projects);
    }

    /**
     * Get upcoming milestones.
     */
    public function getUpcomingMilestones()
    {
        $thirtyDaysFromNow = now()->addDays(30);
        
        $milestones = Milestone::with('project')
            ->where('status', '!=', 'Completed')
            ->where('end_date', '<=', $thirtyDaysFromNow)
            ->orderBy('end_date')
            ->take(4)
            ->get()
            ->map(function ($milestone) {
                return [
                    'project' => $milestone->project->title,
                    'milestone' => $milestone->name,
                    'date' => $milestone->end_date->format('M d, Y'),
                    'status' => $this->getMilestoneStatus($milestone)
                ];
            });
            
        return response()->json($milestones);
    }

    /**
     * Get initials from a name.
     */
    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return $initials;
    }

    /**
     * Get project status based on progress and due date.
     */
    private function getProjectStatus($project)
    {
        if ($project->due_date->isPast()) {
            return 'Delayed';
        }
        
        $totalDuration = $project->start_date->diffInDays($project->due_date);
        $elapsedDuration = $project->start_date->diffInDays(now());
        
        if ($totalDuration === 0) return 'On Track';
        
        $expectedProgress = ($elapsedDuration / $totalDuration) * 100;
        
        if ($project->progress >= $expectedProgress - 10) {
            return 'On Track';
        } else if ($project->progress >= $expectedProgress - 20) {
            return 'At Risk';
        } else {
            return 'Delayed';
        }
    }

    /**
     * Get milestone status based on progress and due date.
     */
    private function getMilestoneStatus($milestone)
    {
        if ($milestone->due_date->isPast()) {
            return 'Delayed';
        }
        
        $daysUntilDue = now()->diffInDays($milestone->end_date);
        
        if ($daysUntilDue <= 7) {
            return 'At Risk';
        } else {
            return 'On Track';
        }
    }
}