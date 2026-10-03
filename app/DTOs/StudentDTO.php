<?php

namespace App\DTOs;

use App\Models\Student;

readonly class StudentDTO
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $dateOfBirth,
        public string $gender,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    public static function fromModel(Student $student): self
    {
        return new self(
            id: $student->id,
            name: $student->name,
            dateOfBirth: $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '',
            gender: $student->gender,
            createdAt: $student->created_at?->toIso8601String(),
            updatedAt: $student->updated_at?->toIso8601String(),
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'],
            dateOfBirth: $data['date_of_birth'],
            gender: $data['gender'],
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'name' => $this->name,
            'date_of_birth' => $this->dateOfBirth,
            'gender' => $this->gender,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ], fn ($value) => $value !== null);
    }
}
