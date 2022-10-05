<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Source\SourceRequest;
use App\Http\Resources\ArtworkResource;
use App\Http\Resources\SourceResource;
use App\Models\Source;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return SourceResource::collection(Source::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SourceRequest $request
     * @return SourceResource
     */
    public function store(SourceRequest $request): SourceResource
    {
        $source = Source::create($request->validated());
        return new SourceResource($source);
    }

    /**
     * Display the specified resource.
     *
     * @param Source $source
     * @return SourceResource
     */
    public function show(Source $source): SourceResource
    {
        return new SourceResource($source);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SourceRequest $request
     * @param Source $source
     * @return SourceResource
     */
    public function update(SourceRequest $request, Source $source): SourceResource
    {
        $source->update($request->validated());
        return new SourceResource($source);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Source $source
     * @return JsonResponse
     */
    public function destroy(Source $source): JsonResponse
    {
        $source->delete();
        return response()->json([
            'message' => 'Source deleted successfully'
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Source $source
     * @return AnonymousResourceCollection
     */
    public function getSourceArtworks(Source $source): AnonymousResourceCollection
    {
        return ArtworkResource::collection($source->artworks);
    }
}
