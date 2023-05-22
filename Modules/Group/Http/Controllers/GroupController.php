<?php

namespace Modules\Group\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Notification;
use Modules\Group\Entities\Group;
use Modules\Group\Http\Requests\CreateGroupRequest;
use Modules\Group\Http\Requests\GetGroupsRequest;
use Modules\Group\Http\Requests\UpdateGroupRequest;
use Modules\Group\Interfaces\GroupRepositoryInterface;
use Modules\Group\Notifications\GroupCreated;
use Modules\Group\Transformers\GroupCategoryResource;
use Modules\Group\Transformers\GroupListingResource;
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

        return GroupListingResource::collection($groups);
    }

    public function getMyGroups(GetGroupsRequest $request)
    {
        $groups = $this->groupRepository->getMyGroups($request->validated());

        return GroupListingResource::collection($groups);
    }

    public function getByInviteLink(string $inviteLink)
    {
        $group = $this->groupRepository->getGroupByInviteLink($inviteLink);

        return is_null($group) ? response(['message' => 'Invalid link!'], 404) : GroupResource::make($group);
    }

    public function createGroup(CreateGroupRequest $request)
    {
        $group = $this->groupRepository->createGroup($request->getSanitized());

        if ($group) {
//            Notification::send(AdminUser::all(), new GroupCreated($group));
            return response([
                'message' => 'Group created successfully, Please wait for admin approval!',
                'group' => GroupResource::make($group)
            ]);
        }

        return $this->failed();
    }

    public function showGroup(int $groupId)
    {
        $group = $this->groupRepository->getGroupById($groupId);
        $this->authorize('show', $group);

        return GroupResource::make($group);
    }

    public function updateGroup(Group $group, UpdateGroupRequest $request)
    {
        $this->authorize('update', $group);

        $affectedRows = $this->groupRepository->updateGroup($group, $request->validated());

        return $affectedRows > 0
            ? response(['message' => 'Group updated successfully', 'group' => GroupResource::make($group->refresh())])
            : $this->failed();
    }

    public function groupCategories()
    {
        $categories = $this->groupRepository->getCategories();

        return GroupCategoryResource::collection($categories);
    }

    public function deleteGroup(Group $group)
    {
        $this->authorize('delete', $group);

        $deleted = $this->groupRepository->deleteGroup($group->id);

        return $deleted ? response(['message' => 'Group deleted successfully!']) : $this->failed();
    }

}
