<?php

namespace Modules\Group\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Policies\Traits\AuthorizesGroupBlackListActions;
use Modules\Group\Entities\BlackList;
use Modules\Group\Entities\Group;
use Modules\Group\Http\Requests\UpdateBlackListPermissionsRequest;
use Modules\Group\Interfaces\BlackListRepositoryInterface;
use Modules\Group\Transformers\BlackListMemberResource;
use Modules\User\Entities\User;

class BlackListController extends Controller
{
    protected BlackListRepositoryInterface $blackListRepository;

    public function __construct(BlackListRepositoryInterface $blackListRepository)
    {
        $this->blackListRepository = $blackListRepository;
    }

    public function index(Group $group)
    {
        /* @see AuthorizesGroupBlackListActions::getBlackList() */
        $this->authorize('getBlackList', $group);

        $blackListMembers = $this->blackListRepository->getBlackList($group);

        return BlackListMemberResource::collection($blackListMembers);
    }

    public function addToBlackList(Group $group, User $user)
    {
        /* @see AuthorizesGroupBlackListActions::addToBlackList() */
        $this->authorize('addToBlackList', [$group, $user]);

        $blackListMember = $this->blackListRepository->addToBlackList($group, $user);

        return $blackListMember
            ? response(['message' => 'Added to blacklist!'])
            : response(['message' => 'Something went wrong!'], 500);
    }

    public function removeFromBlackList(Group $group, BlackList $blackListMember)
    {
        $this->authorize('removeFromBlacklist', [$group, $blackListMember]);

        $removed = $this->blackListRepository->removeFromBlackList($blackListMember);

        return $removed
            ? response(['message' => 'User removed from blacklist!'])
            : response(['message' => 'Something went wrong!'], 500);
    }

    public function updatePermissions(Group $group, BlackList $blackListMember, UpdateBlackListPermissionsRequest $request)
    {
        $this->authorize('updateBlackListPermissions', [$group, $blackListMember]);

        $affectedRows = $this->blackListRepository->updatePermissions($blackListMember, $request->validated());

        return $affectedRows > 0
            ? response(['message' => 'Permissions updated!'])
            : response(['message' => 'Something went wrong!'], 500);
    }
}
