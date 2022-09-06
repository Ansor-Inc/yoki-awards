<?php

namespace Modules\Group\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupAdmin;
use App\Models\User;
use Modules\Group\Http\Requests\UpdateAdminPermissionsRequest;
use Modules\Group\Repositories\Interfaces\GroupAdminRepositoryInterface;
use Modules\Group\Transformers\GroupAdminResource;

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
        $this->groupAdminRepository->assignAsAdmin($group, $user);

        return response(['message' => 'Successfully assigned as admin!']);
    }

    public function remove(Group $group, GroupAdmin $groupAdmin)
    {
        $this->authorize('dischargeAdmin', $group);
        $this->groupAdminRepository->dischargeAdmin($groupAdmin);

        return response(['message' => 'Admin removed successfully!']);
    }

    public function updatePermissions(Group $group, GroupAdmin $groupAdmin, UpdateAdminPermissionsRequest $request)
    {
        $this->authorize('updateAdminPermissions', $group);
        $this->groupAdminRepository->updateAdminPermissions($groupAdmin, $request->validated());

        return response(['message' => 'Permissions updated!']);
    }


}