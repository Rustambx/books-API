<?php

namespace App\Modules\RBAC\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'company_id' => $this->resource->company_id,
            'name' => $this->resource->name,
            'display_name' => __('common.'.$this->resource->name),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
