<?php

namespace App\Http\Resources;

use App\DTOs\StudentDTO;
use App\Transformers\StudentTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $transformer = new StudentTransformer();

        if ($this->resource instanceof StudentDTO) {
            return $transformer->transform($this->resource);
        }

        return parent::toArray($request);
    }
}
