<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        
        return response()->json($users);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,manager,team_member',
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);
        
        return response()->json($user, 201);
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'sometimes|required|in:admin,manager,team_member',
        ]);
        
        $user->update($validated);
        
        return response()->json($user);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Check if user has assigned tasks
        if ($user->tasks()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete user with assigned tasks'
            ], 400);
        }
        
        $user->delete();
        
        return response()->json(null, 204);
    }
    
    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        $user = Auth::user();
        
        return response()->json($user);
    }
    
    /**
     * Get projects assigned to the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function projects()
    {
        $user = Auth::user();
        $projects = $user->projects;
        
        return response()->json($projects);
    }
    
    /**
     * Assign a user to a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignToProject(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'role' => 'required|in:manager,team_member,viewer',
        ]);
        
        $user = User::findOrFail($validated['user_id']);
        $project = Project::findOrFail($validated['project_id']);
        
        // Check if user is already assigned to the project
        if ($project->users()->where('user_id', $user->id)->exists()) {
            $project->users()->updateExistingPivot($user->id, ['role' => $validated['role']]);
        } else {
            $project->users()->attach($user->id, ['role' => $validated['role']]);
        }
        
        return response()->json([
            'message' => 'User assigned to project successfully',
            'user' => $user,
            'project' => $project,
            'role' => $validated['role'],
        ]);
    }
    
    /**
     * Remove a user from a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeFromProject(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);
        
        $user = User::findOrFail($validated['user_id']);
        $project = Project::findOrFail($validated['project_id']);
        
        // Check if user has tasks in the project
        $hasTasks = $user->tasks()->where('project_id', $project->id)->exists();
        
        if ($hasTasks) {
            return response()->json([
                'message' => 'Cannot remove user with assigned tasks in this project'
            ], 400);
        }
        
        $project->users()->detach($user->id);
        
        return response()->json([
            'message' => 'User removed from project successfully'
        ]);
    }
}

