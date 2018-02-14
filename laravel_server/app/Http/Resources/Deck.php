<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Deck extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'hidden_face_image_path' => $this->hidden_face_image_path,
            'active' => $this->active,
            'complete' => $this->complete,
        ];
        return parent::toArray($request);
    }
}
