<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/user/applications",
     *     summary="Display applications of user",
     *     tags={"Application"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function userApplications()
    {
        $userApplications = Application::where('user_id', auth()->id())->get();

        return response()->json(['data' => $userApplications]);
    }

    /**
     * @OA\Post(
     *     path="/api/user/apply",
     *     summary="User can apply for an annonce",
     *     tags={"Application"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function apply(Request $request)
    {
        $request->validate([
            'annonce_id' => 'required|exists:annonces,id',
        ]);

        $existingApplication = Application::where('user_id', auth()->id())
            ->where('annonce_id', $request->annonce_id)
            ->first();

        if ($existingApplication) {
            return response()->json(['message' => 'You have already applied to this announcement.'], 400);
        }

        $application = new Application();
        $application->user_id = auth()->id();
        $application->annonce_id = $request->annonce_id;
        // $application->status = 'pending';
        $application->save();

        return response()->json(['message' => 'Application submitted successfully', 'data' => $application], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/organizer/applications",
     *     summary="Display applications made for the organizer's annonce",
     *     tags={"Application"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index()
    {
        $applications = Application::whereHas('annonce', function ($query) {
            $query->where('organizer_id', auth()->id());
        })->get();

        return response()->json(['data' => $applications]);
    }

    /**
     * @OA\Put(
     *     path="/api/organizer/applications",
     *     summary="Approve or decline a user application",
     *     tags={"Application"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $application = Application::findOrFail($id);
        $application->status = $request->status;
        $application->save();

        return response()->json(['message' => 'Application status updated successfully', 'data' => $application]);
    }
}
