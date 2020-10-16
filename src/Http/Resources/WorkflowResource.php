<?php

namespace Anacreation\Workflow\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkflowResource extends JsonResource
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
            'id'    => $this->id,
            'label' => $this->label,
            'code'  => $this->code,
        ];

        if(in_array('transitions',
                    $this->includes)) {
            $result['transitions'] = TransitionResource::collection($this->transitions);
        }
        
        if(in_array('states',
                    $this->includes)) {
            $result['states'] = StateResource::collection($this->states);
        }

        return $result;
    }

    public function include(array $includes): WorkflowResource {
        $this->includes = $includes;

        $this->resource->load($includes);

        return $this;

    }
}
