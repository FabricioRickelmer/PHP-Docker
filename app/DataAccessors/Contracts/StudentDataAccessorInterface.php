<?php

namespace App\DataAccessors\Contracts;

use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StudentDataAccessorInterface
{
    public function getPaginatedStudents(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function getStudentById(int $id): ?Student;

    public function createStudent(array $data): Student;

    public function updateStudent(int $id, array $data): ?Student;

    public function deleteStudent(int $id): bool;

    public function clearStudentCache(?int $id = null): void;
}
