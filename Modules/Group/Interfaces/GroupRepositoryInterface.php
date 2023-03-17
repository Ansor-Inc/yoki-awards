<?php

namespace Modules\GroupInterfaces\Interfaces;

use Modules\Group\Entities\Group;

interface GroupRepositoryInterface
{
    public function getGroupsExceptMine(array $filters);

    public function getMyGroups(array $filters);

    public function getCategories();

    public function getGroupById(int $id);

    public function getGroupByInviteLink(string $inviteLink);

    public function createGroup(array $payload);

    public function updateGroup(Group $group, array $payload);

    public function deleteGroup(Group $group);
}
