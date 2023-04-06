<?php

namespace Modules\Group\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Group\Entities\Group;
use Modules\Group\Entities\GroupAdmin;
use Modules\Group\Http\Requests\UpdateAdminPermissionsRequest;
use Modules\Group\Interfaces\GroupAdminRepositoryInterface;
use Modules\Group\Transformers\GroupAdminResource;
use Modules\User\Entities\User;

class GroupAdminController extends Controller
{
    protected GroupAdminRepositoryInterface $groupAdminRepository;

    public function __construct(GroupAdminRepositoryInterface $groupAdminRepository)
    {
        $this->groupAdminRepository = $groupAdminRepository;
    }

    public function index(Group $group)
    {
        $this->authorize('getAdmins', $group);
        $admins = $this->groupAdminRepository->getAdmins($group);

        return GroupAdminResource::collection($admins);
    }

    public function create(Group $group, User $user)
    {
        $this->authorize('assignAsAdmin', [$group, $user]);
        $groupAdmin = $this->groupAdminRepository->assignAsAdmin($group, $user);

        return $groupAdmin
            ? response(['message' => 'Successfully assigned as admin!'])
            : response(['message' => 'Something went wrong!'], 500);
    }

    public function updatePermissions(Group $group, GroupAdmin $groupAdmin, UpdateAdminPermissionsRequest $request)
    {
        $this->authorize('updateAdminPermissions', $group);
        $affectedRows = $this->groupAdminRepository->updateAdminPermissions($groupAdmin, $request->validated());

        return $affectedRows > 0
            ? response(['message' => 'Permissions updated!'])
            : response(['message' => 'Something went wrong!'], 500);
    }

    public function remove(Group $group, GroupAdmin $groupAdmin)
    {
        $this->authorize('dischargeAdmin', $group);
        $removed = $this->groupAdminRepository->dischargeAdmin($groupAdmin);

        return $removed
            ? response(['message' => 'Admin removed successfully!'])
            : response(['message' => 'Something went wrong!'], 500);
    }
}
