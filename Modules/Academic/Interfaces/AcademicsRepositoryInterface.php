<?php

namespace Modules\Academic\Interfaces;

interface AcademicsRepositoryInterface
{
    public function getAcademics(string $degree, int|null $perPage);

    public function getDegrees();
}
