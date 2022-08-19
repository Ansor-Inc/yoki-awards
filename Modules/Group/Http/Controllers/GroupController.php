<?php

namespace Modules\Group\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Group\Http\Requests\getGroupsRequest;
use Modules\Group\Http\Requests\GroupCreateRequest;
use Modules\Group\Repositories\Interfaces\GroupRepositoryInterface;
use Modules\Group\Transformers\GroupResource;

class GroupController extends Controller
{
    protected GroupRepositoryInterface $repository;

    public function __construct(GroupRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getGroups(GetGroupsRequest $request)
    {
        $filters = $request->validated();
        $groups = $this->repository->getGroupsExceptMine($filters);

        return GroupResource::collection($groups);
    }


}
