<?php

namespace App\Http\Controllers\Api\v1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikeRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
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
            $user->unlike($likeable);
            $likeable->update([
                'likes_count' => --$likeable->likes_count
            ]);

            return Response::json([
                'message' => "$class_name Unliked Successfully",
                'status' => 'success'
            ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
        }else{
            $user->like($likeable);
            $likeable->update([
                'likes_count' => ++$likeable->likes_count
            ]);

            return Response::json([
                'message' => "$class_name liked Successfully",
                'status' => 'success'
            ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
        }
    }
}
