<?php

namespace Modules\Group\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Group\Entities\Group;
use Modules\Group\Entities\GroupCategory;
use Modules\Group\Repositories\Interfaces\GroupRepositoryInterface;

class GroupRepository implements GroupRepositoryInterface
{
    protected ?Authenticatable $owner;

    public function __construct()
    {
        $this->owner = auth()->user();
    }

    public function getGroupsExceptMine(array $filters = [])
    {
        $query = Group::query()
            ->with(['category:id,title', 'members' => fn($query) => $query->select('users.id', 'users.avatar')->limit(3), 'currentUserMembershipStatus'])
            ->withCount('members')
            ->filter($filters)
            ->whereNot('owner_id', $this->owner->id);

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->limit(100)->get();
    }

    public function getMyGroups(array $filters)
    {
        $query = $this->owner->groups()
            ->withoutGlobalScopes()
            ->with(['category:id,title', 'members' => fn($query) => $query->select('avatar')->limit(3)])
            ->withCount('members')
            ->filter($filters);

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->limit(100)->get();
    }

    public function getCategories()
    {
        return GroupCategory::query()->select('id', 'title')->withCount('groups')->get();
    }

    public function getGroupById(int $id)
    {
        return Group::query()
            ->withCount('posts')
            ->with('currentUserPermissionStatus')
            ->findOrFail($id);
    }

    public function createGroup(array $payload)
    {
        return Group::query()->create($payload);
    }

    public function updateGroup(Group $group, array $payload)
    {
        return $group->update($payload);
    }

    public function deleteGroup(Group $group)
    {
        return $group->delete();
    }

    public function getGroupByInviteLink(string $inviteLink)
    {
        return Group::query()->where('invite_link', $inviteLink)->first();
    }
}