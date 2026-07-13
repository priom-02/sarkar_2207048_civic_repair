<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\IssueAssignment;

class CheckAssignmentOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $assignmentId = $request->route('assignment_id');
        
        if ($assignmentId) {
            $assignment = IssueAssignment::find($assignmentId);
            
            // Check if the assignment exists and if it belongs to the authenticated worker
            if ($assignment && $assignment->worker_id !== auth()->id()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access. This assignment does not belong to you.'
                    ], 403);
                }
                
                abort(403, 'Unauthorized access. This assignment does not belong to you.');
            }
        }

        return $next($request);
    }
}
