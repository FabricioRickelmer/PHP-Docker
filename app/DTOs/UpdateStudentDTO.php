<?php

namespace App\DTOs;

use App\Http\Requests\UpdateStudentRequest;

readonly class UpdateStudentDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $dateOfBirth = null,
        public ?string $gender = null,
    ) {}

    public static function fromRequest(UpdateStudentRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            dateOfBirth: $request->validated('date_of_birth'),
            gender: $request->validated('gender'),
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            dateOfBirth: $data['date_of_birth'] ?? null,
            gender: $data['gender'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'date_of_birth' => $this->dateOfBirth,
            'gender' => $this->gender,
        ], fn ($value) => $value !== null);
    }
}
