<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    private string $token;
    private string $message;

    /**
     * @param $resource
     * @param string $token
     * @param string $message
     */
    public function __construct($resource, string $token, string $message = 'Done')
    {
        $this->token = $token;
        $this->message = $message;

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'token' => $this->token
        ];
    }

    public function with($request): array
    {
        return [
            'status' => 'success',
            'message' => $this->message,
        ];
    }
}
