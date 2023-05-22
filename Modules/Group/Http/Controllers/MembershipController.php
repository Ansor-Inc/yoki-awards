<?php

namespace Modules\Group\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Group\Entities\Group;
use Modules\Group\Http\Requests\GetGroupApprovedMembersRequest;
use Modules\Group\Interfaces\MembershipRepositoryInterface;
use Modules\Group\Transformers\MemberResource;
use Modules\User\Entities\User;

class MembershipController extends Controller
{
    protected MembershipRepositoryInterface $membershipRepository;

    public function __construct(MembershipRepositoryInterface $membershipRepository)
    {
        $this->membershipRepository = $membershipRepository;
    }

    public function groupApprovedMembers(Group $group, GetGroupApprovedMembersRequest $request)
    {
        $this->authorize('getApprovedMembers', $group);

        $members = $this->membershipRepository->getApprovedMembersOfGroup($group, $request->validated());

        return MemberResource::collection($members);
    }

    public function groupPendingMembers(Group $group)
    {
        $this->authorize('getPendingMembers', $group);

        $members = $this->membershipRepository->getPotentialMembersOfGroup($group);

        return MemberResource::collection($members);
    }

    public function joinGroup(Group $group)
    {
        $this->authorize('joinGroup', $group);

        $membership = $this->membershipRepository->createMembership($group, request()->user());

        return $membership->approved ?
            response(['joined' => true, 'message' => "Guruxga muvafaqiyatli qo'shildingiz!"]) :
            response(['joined' => false, 'message' => "Bu guruh xususiy, qo‘shilish so‘rovingiz gurux administratorlariga yuborildi!"]);
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
