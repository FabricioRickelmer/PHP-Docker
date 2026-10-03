<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CreateStudentDTO;
use App\DTOs\UpdateStudentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Services\Contracts\StudentServiceInterface;
use App\Transformers\StudentTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(
        protected StudentServiceInterface $studentService,
        protected StudentTransformer $studentTransformer
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $page = (int) $request->query('page', 1);

        $paginator = $this->studentService->getPaginatedStudents($perPage, $page);

        $items = $this->studentTransformer->transformCollection($paginator->items());

        return response()->json([
            'success' => true,
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        $dto = CreateStudentDTO::fromRequest($request);
        $studentDto = $this->studentService->createStudent($dto);

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully',
            'data' => $this->studentTransformer->transform($studentDto),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $studentDto = $this->studentService->getStudentById($id);

        if (! $studentDto) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->studentTransformer->transform($studentDto),
        ]);
    }

    public function update(UpdateStudentRequest $request, int $id): JsonResponse
    {
        $dto = UpdateStudentDTO::fromRequest($request);
        $studentDto = $this->studentService->updateStudent($id, $dto);

        if (! $studentDto) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully',
            'data' => $this->studentTransformer->transform($studentDto),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->studentService->deleteStudent($id);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found or could not be deleted',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully',
        ], 200);
    }
}
