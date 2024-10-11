<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);

        $image = [];
        foreach ($this->images as $media){
            $image[] = new MediaResource($media);
        }
        return [
            'title' => $this->title,
            'exam_name' => $this->when($this->exams()->exists(), $this->exams->name),
//            'image' => $this->when($this->questionImage()->exists(), $this->questionImage),
            'image' => $image,
            'ip' => $request->ip()
        ];
    }
}
