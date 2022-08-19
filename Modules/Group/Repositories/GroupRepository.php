<?php

namespace Modules\Group\Repositories;

use App\Models\Group;
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
            ->onlyListingFields()
            ->whereNot('owner_id', $this->owner->id);

        if ($perPage) {
            return $query->paginate($perPage);
        }

        return $query->get();
    }

    public function getMyGroups($perPage = null)
    {
        $query = Group::query()
            ->onlyListingFields()
            ->where('owner_id', $this->owner->id);
    }

    public function createGroup(array $payload)
    {
        return Group::query()->create($payload);
    }

    public function deleteGroup(int $id)
    {
        Group::destroy($id);
    }

    protected function performQuery($query)
    {

    }

}