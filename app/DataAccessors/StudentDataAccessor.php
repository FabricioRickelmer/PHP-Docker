<?php

namespace App\DataAccessors;

use App\DataAccessors\Contracts\StudentDataAccessorInterface;
use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class StudentDataAccessor implements StudentDataAccessorInterface
{
    protected const CACHE_TTL_SECONDS = 3600; // 1 hour
    protected const CACHE_KEY_SINGLE = 'students:id:';
    protected const CACHE_KEY_PAGE = 'students:page:';

    public function __construct(
        protected StudentRepositoryInterface $studentRepository
    ) {}

    public function getPaginatedStudents(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        $cacheKey = self::CACHE_KEY_PAGE . "{$page}:per_page:{$perPage}";

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($perPage) {
            return $this->studentRepository->paginate($perPage);
        });
    }

    public function getStudentById(int $id): ?Student
    {
        $cacheKey = self::CACHE_KEY_SINGLE . $id;

        return Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($id) {
            return $this->studentRepository->findById($id);
        });
    }

    public function createStudent(array $data): Student
    {
        $student = $this->studentRepository->create($data);
        $this->clearStudentCache($student->id);

        return $student;
    }

    public function updateStudent(int $id, array $data): ?Student
    {
        $student = $this->studentRepository->update($id, $data);

        if ($student) {
            $this->clearStudentCache($id);
        }

        return $student;
    }

    public function deleteStudent(int $id): bool
    {
        $deleted = $this->studentRepository->delete($id);

        if ($deleted) {
            $this->clearStudentCache($id);
        }

        return $deleted;
    }

    public function clearStudentCache(?int $id = null): void
    {
        if ($id !== null) {
            Cache::forget(self::CACHE_KEY_SINGLE . $id);
        }

        // Flush pagination / list caches or use Redis pattern scan if available
        // Simple flush/forget mechanism for list keys:
        for ($i = 1; $i <= 50; $i++) {
            Cache::forget(self::CACHE_KEY_PAGE . "{$i}:per_page:15");
            Cache::forget(self::CACHE_KEY_PAGE . "{$i}:per_page:10");
            Cache::forget(self::CACHE_KEY_PAGE . "{$i}:per_page:25");
        }
    }
}
