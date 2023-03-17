<?php

namespace Modules\Academic\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AcademicResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'user' => [
                'id' => $this->id,
                'avatar' => $this->avatar,
                'fullname' => $this->fullname,
            ],
            'read_books_count' => $this->read_books_count,
            'words_count' => (int)$this->read_books_page_count * 500,
            'listen_time' => 0
        ];
    }
}
