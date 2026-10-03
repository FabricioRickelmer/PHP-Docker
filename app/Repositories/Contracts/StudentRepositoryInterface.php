<?php

namespace App\Repositories\Contracts;

use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StudentRepositoryInterface
{
    public function all(): array;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Student;

    public function create(array $data): Student;

    public function update(int $id, array $data): ?Student;

    public function delete(int $id): bool;
}
