<?php

namespace App\Http\Resources\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ExamCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'start' => $this->start,
            'end' => $this->image,
            'time' => $this->end,
            'degree' => $this->degree,
            'is_closed' => $this->is_closed,
            'questtions' => $this->when($this->questtions()->exists(), $this->questtions->title),
            'type' => $this->when($this->examTypes()->exists(), $this->examType->name),
            'group' => $this->when($this->groups()->exists(), $this->groups->name),
            'teacher' => $this->when($this->teachers()->exists(), $this->teachers->name),
//            'image' => $this->when($this->examImage()->exists(), $this->examImage),

        ];
    }

}
