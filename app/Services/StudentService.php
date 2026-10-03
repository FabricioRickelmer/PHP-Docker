<?php

namespace App\Services;

use App\DataAccessors\Contracts\StudentDataAccessorInterface;
use App\DTOs\CreateStudentDTO;
use App\DTOs\StudentDTO;
use App\DTOs\UpdateStudentDTO;
use App\Services\Contracts\StudentServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StudentService implements StudentServiceInterface
{
    public function __construct(
        protected StudentDataAccessorInterface $dataAccessor
    ) {}

    public function getPaginatedStudents(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        $paginator = $this->dataAccessor->getPaginatedStudents($perPage, $page);

        // Transform models inside paginator into StudentDTO objects
        $paginator->through(fn ($student) => StudentDTO::fromModel($student));

        return $paginator;
    }

    public function getStudentById(int $id): ?StudentDTO
    {
        $student = $this->dataAccessor->getStudentById($id);

        if (! $student) {
            return null;
        }

        return StudentDTO::fromModel($student);
    }

    public function createStudent(CreateStudentDTO $dto): StudentDTO
    {
        // Execute creation inside an explicit ACID transaction block
        return DB::transaction(function () use ($dto) {
            $student = $this->dataAccessor->createStudent($dto->toArray());

            return StudentDTO::fromModel($student);
        });
    }

    public function updateStudent(int $id, UpdateStudentDTO $dto): ?StudentDTO
    {
        // Execute update inside an explicit ACID transaction block
        return DB::transaction(function () use ($id, $dto) {
            $student = $this->dataAccessor->updateStudent($id, $dto->toArray());

            if (! $student) {
                return null;
            }

            return StudentDTO::fromModel($student);
        });
    }

    public function deleteStudent(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            return $this->dataAccessor->deleteStudent($id);
        });
    }
}
