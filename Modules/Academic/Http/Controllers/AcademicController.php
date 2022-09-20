<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Academic\Http\Requests\GetAcademicsRequest;
use Modules\Academic\Repositories\Interfaces\AcademicsRepositoryInterface;
use Modules\Academic\Transformers\AcademicResource;

class AcademicController extends Controller
{
    protected AcademicsRepositoryInterface $repository;

    public function __construct(AcademicsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAcademics(GetAcademicsRequest $request)
    {
        $academics = $this->repository->getAcademics($request->input('degree'), $request->input('per_page'));

        return AcademicResource::collection($academics);
    }

    public function getDegrees()
    {
        $degrees = $this->repository->getDegrees();

        return response(['data' => $degrees]);
    }
}
