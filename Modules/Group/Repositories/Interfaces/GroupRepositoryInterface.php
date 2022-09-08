<?php

namespace Modules\Group\Repositories\Interfaces;

use App\Models\Group;

interface GroupRepositoryInterface
{
    public function getGroupsExceptMine(array $filters);

    public function getMyGroups(array $filters);

    public function getCategories();

    public function getGroupById(int $id);

    public function createGroup(array $payload);

    public function updateGroup(Group $group, array $payload);

    public function deleteGroup(Group $group);
}