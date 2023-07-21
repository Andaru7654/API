<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'user_id'=> $this->user_id,
            'comments_content'=> $this->comments_content,
            'comentator'=> $this->whenLoaded('comentator'),
            'created_at'=> date_format($this->created_at,"Y/m/d H:i:s"),
        ];
    }
}
