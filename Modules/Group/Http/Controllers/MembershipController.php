<?php

namespace Modules\Group\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Modules\Group\Repositories\Interfaces\MembershipRepositoryInterface;
use Modules\Group\Transformers\MemberResource;

class MembershipController extends Controller
{
    protected MembershipRepositoryInterface $membershipRepository;

    public function __construct(MembershipRepositoryInterface $membershipRepository)
    {
        $this->membershipRepository = $membershipRepository;
    }

    public function groupApprovedMembers(Group $group)
    {
        $this->authorize('getMembers', $group);

        $members = $this->membershipRepository->getApprovedMembersOfGroup($group);

        return MemberResource::collection($members);
    }

    public function groupPendingMembers(Group $group)
    {
        $this->authorize('getMembers', $group);

        $members = $this->membershipRepository->getPotentialMembersOfGroup($group);

        return MemberResource::collection($members);
    }

    public function joinGroup(Group $group)
    {
        $this->authorize('joinGroup', $group);
        $membership = $this->membershipRepository->createMembership($group, request()->user());

        return $membership->approved ?
            response(['joined' => true, 'message' => 'Successfully joined the group!']) :
            response(['joined' => false, 'message' => 'This group is private, your join request has been sent to administrators!']);
    }

    public function leaveGroup(Group $group)
    {
        $this->authorize('leaveGroup', $group);
        $this->membershipRepository->removeMembership($group, request()->user());

        return ['leaved' => true, 'message' => 'Group leaved successfully'];
    }

    public function acceptMember(Group $group, User $user)
    {
        $this->authorize('acceptMember', [$group, $user]);
        $this->membershipRepository->acceptMembership($group, $user);

        return response(['message' => 'Accepted the new member!']);
    }

    public function rejectMember(Group $group, User $user)
    {
        $this->authorize('rejectMember', [$group, $user]);
        $this->membershipRepository->rejectMembership($group, $user);

        return response(['message' => 'Rejected the new member!']);
    }

    public function removeMember(Group $group, User $user)
    {
        $this->authorize('removeMember', [$group, $user]);
        $this->membershipRepository->removeMembership($group, $user);

        return response(['message' => 'The user has been removed from group!']);
    }


}