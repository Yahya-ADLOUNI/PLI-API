<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Source\SourceRequest;
use App\Http\Resources\ArtworkResource;
use App\Http\Resources\SourceResource;
use App\Models\Source;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
     * @param SourceRequest $request
     * @return void
     */
    public function store(SourceRequest $request)
    {
        //
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
     * @param SourceRequest $request
     * @param Source $source
     * @return void
     */
    public function update(SourceRequest $request, Source $source)
    {
        //
    }

    /**
     * @param Source $source
     * @return void
     */
    public function destroy(Source $source)
    {
        //
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
