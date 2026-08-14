<?php

namespace App\Repositories\Eloquent;

use App\Models\Specialty;
use App\Repositories\Contracts\SpecialtyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SpecialtyRepository implements SpecialtyRepositoryInterface
{
    public function __construct(
        protected Specialty $model
    ) {}

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage);
    }

    public function findById(int $id): ?Specialty
    {
        return $this->model->find($id);
    }

    public function create(array $data): Specialty
    {
        return $this->model->create($data);
    }

    public function update(Specialty $specialty, array $data): Specialty
    {
        $specialty->update($data);
        return $specialty;
    }

    public function delete(Specialty $specialty): bool
    {
        return $specialty->delete();
    }

    public function generateNextCode(): string
    {
        $latest = $this->model->withTrashed()->latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        
        return 'CK-' . str_pad($nextId, 4, '0', STR_PAD_LEFT); // Kết quả: CK-0001, CK-0002...
    }
}