<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StudentResource;
use App\Student;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function index()
    {
        $students = QueryBuilder::for(Student::class)
            ->get();

        return StudentResource::collection($students);
    }
}
