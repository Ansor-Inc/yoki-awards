<?php

namespace Modules\Group\Repositories\Interfaces;

interface GroupRepositoryInterface
{
    public function getGroupsExceptMine(array $filters);

    public function getMyGroups(array $filters);

    public function getCategories();

    public function getGroupById(int $id);

    public function createGroup(array $payload);

    public function updateGroup(int $id, array $payload);

    public function deleteGroup(int $id);
}