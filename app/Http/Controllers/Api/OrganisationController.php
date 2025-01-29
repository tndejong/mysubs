<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrganisationController extends Controller
{
    /**
     * Display a listing of the organisations.
     */
    public function index(): JsonResponse
    {
        $organisations = auth()->user()->organisations;
        return response()->json($organisations);
    }

    /**
     * Display the specified organisation.
     */
    public function show(Organisation $organisation): JsonResponse
    {
        // Check if user belongs to this organisation
        if (!auth()->user()->canAccessTenant($organisation)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($organisation);
    }
}
