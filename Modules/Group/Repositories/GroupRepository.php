<?php

namespace Modules\Group\Repositories;

use App\Models\Group;
use App\Models\GroupCategory;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Group\Repositories\Interfaces\GroupRepositoryInterface;

class GroupRepository implements GroupRepositoryInterface
{
    protected ?Authenticatable $owner;

    public function __construct()
    {
        $this->owner = auth('sanctum')->user();
    }

    public function getGroupsExceptMine(array $filters = [])
    {
        $query = Group::query()
            ->select('id', 'title', 'group_category_id', 'member_limit')
            ->with(['category:id,title', 'members' => fn($query) => $query->limit(3)])
            ->withCount('members')
            ->filter($filters)
            ->whereNot('owner_id', $this->owner->id);

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->limit(100)->get();
    }

    public function getMyGroups(array $filters)
    {
        $query = $this->owner->groups()
            ->withoutGlobalScopes()
            ->select('id', 'title', 'group_category_id', 'status', 'member_limit')
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
        return Group::query()->findOrFail($id);
    }

    public function createGroup(array $payload)
    {
        return Group::query()->create($payload);
    }

    public function updateGroup(int $id, array $payload)
    {
        Group::query()->where('id', $id)->update($payload);
    }


    public function deleteGroup(int $id)
    {
        Group::destroy($id);
    }
}