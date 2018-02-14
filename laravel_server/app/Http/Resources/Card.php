<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Card extends Resource
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
            'value' => $this->value,
            'suite' => $this->suite,
            'deck_id' => $this->deck_id,
            'path' => $this->path,
        ];
        return parent::toArray($request);
    }
}
