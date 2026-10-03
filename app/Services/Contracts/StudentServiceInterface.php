<?php

namespace App\Services\Contracts;

use App\DTOs\CreateStudentDTO;
use App\DTOs\StudentDTO;
use App\DTOs\UpdateStudentDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StudentServiceInterface
{
    public function getPaginatedStudents(int $perPage = 15, int $page = 1): LengthAwarePaginator;

    public function getStudentById(int $id): ?StudentDTO;

    public function createStudent(CreateStudentDTO $dto): StudentDTO;

    public function updateStudent(int $id, UpdateStudentDTO $dto): ?StudentDTO;

    public function deleteStudent(int $id): bool;
}
