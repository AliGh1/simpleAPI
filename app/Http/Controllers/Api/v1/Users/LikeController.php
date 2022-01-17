<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikeRequest;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use function auth;
use function class_basename;

class LikeController extends Controller
{
    public function like(LikeRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $likeable = $request->likeable();
        $class_name = class_basename($likeable);

        if($user->isLiked($likeable)){
            DB::transaction(function () use ($user, $likeable) {
                $user->unlike($likeable);
                $likeable->update([
                    'likes_count' => --$likeable->likes_count
                ]);
            });

            return response()->success("$class_name unliked Successfully", Response::HTTP_OK);

        }else{
            DB::transaction(function () use ($user, $likeable) {
                $user->like($likeable);
                $likeable->update([
                    'likes_count' => ++$likeable->likes_count
                ]);
            });

            return response()->success("$class_name liked Successfully", Response::HTTP_OK);
        }
    }
}
