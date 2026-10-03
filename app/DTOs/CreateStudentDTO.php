<?php

namespace App\DTOs;

use App\Http\Requests\StoreStudentRequest;

readonly class CreateStudentDTO
{
    public function __construct(
        public string $name,
        public string $dateOfBirth,
        public string $gender,
    ) {}

    public static function fromRequest(StoreStudentRequest $request): self
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
            name: $data['name'],
            dateOfBirth: $data['date_of_birth'],
            gender: $data['gender'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'date_of_birth' => $this->dateOfBirth,
            'gender' => $this->gender,
        ];
    }
}
