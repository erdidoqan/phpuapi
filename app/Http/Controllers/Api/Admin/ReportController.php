<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\ReportResource;
use App\Report;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\Controller;


class ReportController extends Controller
{
    public function index()
    {
        $reports = QueryBuilder::for(Report::class)->defaultSort('-updated_at')->with('user')->paginate();

        return ReportResource::collection($reports);
    }
}
