<?php

namespace Anacreation\Workflow\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    private $includes = [];

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        $result = [
            'id' => $this->id,
            'label' => $this->label,
            'code'  => $this->code,
        ];

        foreach($this->includes as $include) {
            $result[$include] = $this->{$include};
        }

        return $result;
    }

    public function include(array $includes): StateResource {
        $this->includes = $includes;

        $this->resource->load($includes);

        return $this;

    }
}
