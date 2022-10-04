<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Interest\InterestRequest;
use App\Http\Resources\InterestResource;
use App\Models\Interest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class InterestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return InterestResource::collection(Interest::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param InterestRequest $request
     * @return InterestResource
     */
    public function store(InterestRequest $request): InterestResource
    {
        $interest = Interest::create($request->validated());
        return new InterestResource($interest);
    }

    /**
     * Display the specified resource.
     *
     * @param Interest $interest
     * @return InterestResource
     */
    public function show(Interest $interest): InterestResource
    {
        return new InterestResource($interest);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param InterestRequest $request
     * @param Interest $interest
     * @return InterestResource
     */
    public function update(InterestRequest $request, Interest $interest): InterestResource
    {
        $interest->update($request->validated());
        return new InterestResource($interest);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Interest $interest
     * @return JsonResponse
     */
    public function destroy(Interest $interest): JsonResponse
    {
        $interest->delete();
        return response()->json([
            'message' => 'Interest deleted successfully'
        ], Response::HTTP_OK);
    }
}
