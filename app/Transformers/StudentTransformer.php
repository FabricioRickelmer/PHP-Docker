<?php

namespace App\Transformers;

use App\DTOs\StudentDTO;
use DateTime;

class StudentTransformer
{
    public function transform(StudentDTO $dto): array
    {
        return [
            'id' => $dto->id,
            'name' => $dto->name,
            'date_of_birth' => $dto->dateOfBirth,
            'age' => $this->calculateAge($dto->dateOfBirth),
            'gender' => $dto->gender,
            'formatted_gender' => ucfirst($dto->gender),
            'created_at' => $dto->createdAt,
            'updated_at' => $dto->updatedAt,
        ];
    }

    public function transformCollection(iterable $dtos): array
    {
        $transformed = [];
        foreach ($dtos as $dto) {
            if ($dto instanceof StudentDTO) {
                $transformed[] = $this->transform($dto);
            }
        }

        return $transformed;
    }

    private function calculateAge(string $dob): int
    {
        if (empty($dob)) {
            return 0;
        }

        $birthDate = new DateTime($dob);
        $today = new DateTime('today');

        return $birthDate->diff($today)->y;
    }
}
