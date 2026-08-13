<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseResourceCollection extends ResourceCollection
{
    /**
     * Tự động map từng item trong collection qua Resource tương ứng (nếu có)
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'Lấy danh sách thành công',
            'data'    => $this->collection,
            'meta'    => [
                'current_page' => $this->currentPage(),
                'last_page'    => $this->lastPage(),
                'per_page'     => $this->perPage(),
                'total'        => $this->total(),
            ],
        ];
    }
}