<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Academic\Http\Requests\GetAcademicsRequest;
use Modules\Academic\Interfaces\AcademicsRepositoryInterface;
use Modules\Academic\Transformers\AcademicResource;
use Modules\Academic\Transformers\DegreeResource;

class AcademicController extends Controller
{
    public function __construct(protected AcademicsRepositoryInterface $repository)
    {
    }

    public function getAcademics(GetAcademicsRequest $request)
    {
        $academics = $this->repository->getAcademics($request->input('degree'), $request->input('per_page'));

        return AcademicResource::collection($academics);
    }

    public function getDegrees()
    {
        $degrees = $this->repository->getDegrees();

        return DegreeResource::collection($degrees);
    }
}
