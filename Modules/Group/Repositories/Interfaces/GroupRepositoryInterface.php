<?php

namespace Modules\Group\Repositories\Interfaces;

interface GroupRepositoryInterface
{
    public function getGroupsExceptMine(array $filters);

    public function getMyGroups(array $filters);

    public function createGroup(array $payload);

    public function deleteGroup(int $id);
}