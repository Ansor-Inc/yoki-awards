<?php

namespace App\Policies\Responses;

use Illuminate\Auth\Access\Response;

class GroupPolicyResponse
{
    public static function notOwnerOfTheGroup(): Response
    {
        return Response::deny(__('group.you_are_not_the_owner_or_member_of_group'));
    }

    public static function notOwnerOrMemberOfTheGroup(): Response
    {
        return Response::deny(__('group.you_are_not_the_owner_or_member_of_group'));
    }

    public static function groupIsFull(): Response
    {
        return Response::deny(__('group.this_group_is_full'));
    }

    public static function isNotMemberOfTheGroup(): Response
    {
        return Response::deny(__('group.this_user_is_not_member_of_this_group'));
    }

    public static function hasNotRequestedToJoinYet(): Response
    {
        return Response::deny(__('group.this_user_has_not_requested_yet'));
    }

    public static function hasAlreadyBeenAccepted(): Response
    {
        return Response::deny(__('group.this_user_has_already_been_accepted'));
    }

    public static function hasBeenRejectedToJoin(): Response
    {
        return Response::deny(__('group.this_user_has_been_rejected_to_join'));
    }

    public static function dontHaveEnoughPrivilege(): Response
    {
        return Response::deny(__('group.do_not_have_enough_privilege'));
    }

    public static function hasAlreadyRequestedToJoin(): Response
    {
        return Response::deny(__('group.have_already_requested_to_join'));
    }

    public static function isOwnerOfTheGroup(): Response
    {
        return Response::deny(__('group.you_are_the_owner'));
    }

    public static function notMemberOfTheGroup(): Response
    {
        return Response::deny(__('group.you_are_not_the_member'));
    }
}