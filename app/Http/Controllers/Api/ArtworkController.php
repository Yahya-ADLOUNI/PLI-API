<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Artwork\ArtworkRequest;
use App\Http\Requests\IMDB\IMDBRequest;
use App\Http\Requests\Spotify\SpotifyRequest;
use App\Http\Resources\ArtworkResource;
use App\Http\Resources\InterestResource;
use App\Http\Resources\UserResource;
use App\Models\Artwork;
use App\Services\IMDBService;
use App\Services\SpotifyService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ArtworkController extends Controller
{
    private SpotifyService $spotifyService;
    private IMDBService $IMDBService;

    public function __construct(SpotifyService $spotifyService, IMDBService $IMDBService)
    {
        $this->spotifyService = $spotifyService;
        $this->IMDBService = $IMDBService;
    }

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

    /**
     * @param SpotifyRequest $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function getSpotify(SpotifyRequest $request): JsonResponse
    {
        $params = $request->validated();
        if (isset($params['input'])) $jsonData = $this->spotifyService
            ->search($params['input'], $params['offset'] ?? null);
        else $jsonData = $this->spotifyService->random($params['offset'] ?? null);
        if (!$jsonData) return response()
            ->json(['message' => 'Error fetching data from Spotify'], Response::HTTP_SERVICE_UNAVAILABLE);
        $data = $this->spotifyService->parseSpotify($jsonData);
        return response()->json(['data' => $data], Response::HTTP_OK);
    }

    /**
     * @param IMDBRequest $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function getIMDB(IMDBRequest $request): JsonResponse
    {
        $params = $request->validated();
        if (isset($params['input'])) $jsonData = $this->IMDBService->search($params['input']);
        else $jsonData = $this->IMDBService->random();
        $data = $this->IMDBService->parseIMDB($jsonData);
        return response()->json([
            'data' => $data
        ], Response::HTTP_OK);
    }

    public function feed(Request $request): JsonResponse
    {
        $params = $request->validate(['sort' => 'nullable|string', 'offset' => 'nullable|integer']);
        if (array_key_exists('offset', $params)) {
            $spotifyData = $this->spotifyService->random($params['offset'] ?? null);
            $IMDBData = $this->IMDBService->random();
        } else {
            $spotifyData = $this->spotifyService->random();
            $IMDBData = $this->IMDBService->latest();
        }
        $spotify = $this->spotifyService->parseSpotify($spotifyData, true);
        $IMDB = $this->IMDBService->parseIMDB($IMDBData, true);
        $result = array_merge($IMDB, $spotify);
        shuffle($result);
        return response()->json(['data' => $result], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Artwork $artwork
     * @return AnonymousResourceCollection
     */
    public function getArtworkInterests(Artwork $artwork): AnonymousResourceCollection
    {
        return InterestResource::collection($artwork->interests);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Artwork $artwork
     * @return AnonymousResourceCollection
     */
    public function getArtworkUsers(Artwork $artwork): AnonymousResourceCollection
    {
        return UserResource::collection($artwork->users);
    }
}
