<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;


class TagLocalesCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->first()->id,
            'name'=>$this->first()->name,
            'tag_name'=>$this->first()->tagtypes->name,
            'image'=>$this->first()->image,
            'tag_locals'=>[
                'name'=>$this->first()->tag_locals->name,
                'lang'=>$this->first()->tag_locals->lang,
                'description'=>$this->first()->tag_locals->description
            ]
        ];
    }
}
