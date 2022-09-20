<?php

namespace Modules\Academic\Repositories\Interfaces;

use Modules\User\Enums\UserDegree;

interface AcademicsRepositoryInterface
{
    public function getAcademics(string $degree, int|null $perPage = 0);

    public function getDegrees();
}