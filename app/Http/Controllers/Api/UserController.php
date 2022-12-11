<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\OneSeedRequest;
use App\Http\Requests\User\ProfileSeedRequest;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\InterestResource;
use App\Http\Resources\UserResource;
use App\Models\Artwork;
use App\Models\Interest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return UserResource
     */
    public function store(UserRequest $request): UserResource
    {
        $user = User::create($request->validated());
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UserRequest $request, User $user): UserResource
    {
        $user->update($request->validated());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ], Response::HTTP_OK);
    }

    /**
     * @param ProfileSeedRequest $profileSeed
     * @param User $user
     * @return JsonResponse
     */
    public function seedProfile(ProfileSeedRequest $profileSeed, User $user): JsonResponse
    {
        try {
            foreach ($profileSeed->validated('data') as $artwork) $this->userService->addArtwork($artwork, $user);
            return response()->json(['message' => 'Added artworks'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param OneSeedRequest $oneSeedRequest
     * @param User $user
     * @return JsonResponse
     */
    public function addArtwork(OneSeedRequest $oneSeedRequest, User $user): JsonResponse
    {
        try {
            $this->userService->addArtwork($oneSeedRequest->validated(), $user);
            return response()->json(['message' => 'Added artwork'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param User $user
     * @param Artwork $artwork
     * @return JsonResponse
     */
    public function removeArtwork(User $user, Artwork $artwork): JsonResponse
    {
        try {
            $already = $user->artworks()->where('artwork_id', $artwork->id);
            if ($already->count() !== 0) $user->artworks()->detach($artwork->id);
            return response()->json(['message' => 'Removed artwork'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @return AnonymousResourceCollection
     */
    public function getUserInterests(User $user): AnonymousResourceCollection
    {
        return InterestResource::collection($user->interests);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @return AnonymousResourceCollection
     */
    public function getUserArtworks(User $user): AnonymousResourceCollection
    {
        return InterestResource::collection($user->artworks);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @param Artwork $artwork
     * @return JsonResponse
     */
    public function putUserArtworks(User $user, Artwork $artwork): JsonResponse
    {
        $user->artworks()->attach($artwork);
        return response()->json([
            'message' => 'Artwork added successfully'
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $user
     * @param Interest $interest
     * @return JsonResponse
     */
    public function putUserInterests(User $user, Interest $interest): JsonResponse
    {
        $user->interests()->attach($interest);
        return response()->json([
            'message' => 'Interest added successfully'
        ], Response::HTTP_OK);
    }
}
