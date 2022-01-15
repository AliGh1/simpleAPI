<?php

namespace App\Http\Resources\v1;

use App\Traits\HasImage;
use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'body' => $this->body,
            'likes_count' => $this->likes_count,
            'comments_count' => $this->comments_count
        ];
    }

    public function with($request)
    {
        return ['status' => 'success'];
    }
}
