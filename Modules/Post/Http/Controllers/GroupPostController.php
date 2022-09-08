<?php

namespace Modules\Post\Http\Controllers;

use App\Models\Group;
use Illuminate\Routing\Controller;

class GroupPostController extends Controller
{
    public function index(Group $group)
    {
        $this->authorize('seePosts', $group);

        return view('post::index');
    }


}
