<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'title' => $this->title,
            'description' => $this->description,
            'upload_file' => $this->upload_file,
            'created_at' => $this->created_at->translatedFormat('d F Y'),
        ];
    }
}
