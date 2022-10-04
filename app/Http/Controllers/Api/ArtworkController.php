<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Artwork\ArtworkRequest;
use App\Http\Resources\ArtworkResource;
use App\Models\Artwork;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ArtworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ArtworkResource::collection(Artwork::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArtworkRequest $request
     * @return ArtworkResource
     */
    public function store(ArtworkRequest $request): ArtworkResource
    {
        $artwork = Artwork::create($request->validated());
        return new ArtworkResource($artwork);
    }

    /**
     * Display the specified resource.
     *
     * @param Artwork $artwork
     * @return ArtworkResource
     */
    public function show(Artwork $artwork): ArtworkResource
    {
        return new ArtworkResource($artwork);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArtworkRequest $request
     * @param Artwork $artwork
     * @return ArtworkResource
     */
    public function update(ArtworkRequest $request, Artwork $artwork): ArtworkResource
    {
        $artwork->update($request->validated());
        return new ArtworkResource($artwork);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Artwork $artwork
     * @return JsonResponse
     */
    public function destroy(Artwork $artwork): JsonResponse
    {
        $artwork->delete();
        return response()->json([
            'message' => 'Artwork deleted successfully'
        ], Response::HTTP_OK);
    }
}
