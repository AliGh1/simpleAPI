<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Users\LikeRequest;
use App\Interfaces\Likeable;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use function auth;
use function class_basename;

class LikeController extends Controller
{
    /**
     * @param LikeRequest $request
     * @return JsonResponse
     */
    public function like(LikeRequest $request): JsonResponse
    {
        $user = auth()->user();
        $likeable = $request->likeable();
        $class_name = class_basename($likeable);

        if($user->isLiked($likeable)){
            return $this->unLike($user, $likeable, $class_name);

        }else{
            return $this->addLike($user, $likeable, $class_name);
        }
    }

    /**
     * @param User $user
     * @param Likeable $likeable
     * @param string $class_name
     * @return JsonResponse
     */
    private function unLike(User $user, Likeable $likeable, string $class_name): JsonResponse
    {
        DB::transaction(function () use ($user, $likeable) {
            $user->unlike($likeable);
            $likeable->update([
                'likes_count' => --$likeable->likes_count
            ]);
        });

        return response()->success("$class_name unliked Successfully", Response::HTTP_OK);
    }

    /**
     * @param User $user
     * @param Likeable $likeable
     * @param string $class_name
     * @return JsonResponse
     */
    private function addLike(User $user, Likeable $likeable, string $class_name): JsonResponse
    {
        DB::transaction(function () use ($user, $likeable) {
            $user->like($likeable);
            $likeable->update([
                'likes_count' => ++$likeable->likes_count
            ]);
        });

        return response()->success("$class_name liked Successfully", Response::HTTP_OK);
    }
}
