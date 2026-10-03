<?php

namespace App\Repositories\Eloquent;

use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StudentRepository implements StudentRepositoryInterface
{
    public function all(): array
    {
        return Student::all()->all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Student::query()->latest('id')->paginate($perPage);
    }

    public function findById(int $id): ?Student
    {
        return Student::find($id);
    }

    public function create(array $data): Student
    {
        return DB::transaction(function () use ($data) {
            return Student::create([
                'name' => $data['name'],
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
            ]);
        });
    }

    public function update(int $id, array $data): ?Student
    {
        return DB::transaction(function () use ($id, $data) {
            $student = Student::find($id);

            if (! $student) {
                return null;
            }

            $student->update($data);

            return $student->fresh();
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $student = Student::find($id);

            if (! $student) {
                return false;
            }

            return (bool) $student->delete();
        });
    }
}
