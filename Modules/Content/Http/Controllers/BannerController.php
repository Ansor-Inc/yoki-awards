<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Content\Entities\Banner;
use Modules\Content\Http\Requests\BannerRequest;
use Modules\Content\Transformers\BannerResource;

class BannerController extends Controller
{
    public function index(BannerRequest $request)
    {
        return BannerResource::collection($this->getBanners($request));
    }

    protected function getBanners(Request $request)
    {
        $query = Banner::query()->latest();

        return $request->has('limit')
            ? $query->limit($request->integer('limit'))->get()
            : $query->get();
    }
}
