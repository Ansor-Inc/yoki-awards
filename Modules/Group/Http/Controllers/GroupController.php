<?php

namespace Modules\Group\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupCategory;
use Modules\Group\Http\Requests\getGroupsRequest;
use Modules\Group\Http\Requests\CreateGroupRequest;
use Modules\Group\Http\Requests\UpdateGroupRequest;
use Modules\Group\Repositories\Interfaces\GroupRepositoryInterface;
use Modules\Group\Repositories\Interfaces\MembershipRepositoryInterface;
use Modules\Group\Transformers\GroupCategoryResource;
use Modules\Group\Transformers\GroupResource;

class GroupController extends Controller
{
    protected GroupRepositoryInterface $groupRepository;

    public function __construct(GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getGroups(GetGroupsRequest $request)
    {
        $groups = $this->groupRepository->getGroupsExceptMine($request->validated());

        return GroupResource::collection($groups);
    }

    public function getMyGroups(GetGroupsRequest $request)
    {
        $groups = $this->groupRepository->getMyGroups($request->validated());

        return GroupResource::collection($groups);
    }

    public function createGroup(CreateGroupRequest $request)
    {
        $group = $this->groupRepository->createGroup($request->getSanitized());

        return [
            'message' => 'Group created successfully, Please wait for admin approval!',
            'group' => GroupResource::make($group)
        ];
    }

    public function updateGroup(Group $group, UpdateGroupRequest $request)
    {
        $this->authorize('update', $group);

        $this->groupRepository->updateGroup($group->id, $request->getSanitized());

        return [
            'message' => 'Group updated successfully'
        ];
    }

    public function groupCategories()
    {
        $categories = $this->groupRepository->getCategories();

        return GroupCategoryResource::collection($categories);
    }

    public function deleteGroup(Group $group)
    {
        $this->authorize('delete', $group);
        $this->repository->deleteGroup($group->id);

        return ['message' => 'Group deleted successfully!'];
    }

}
