<?php

namespace App\Http\Controllers;

use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $this->validateRequest($request);

        return BannerResource::collection($this->getBanners($request));
    }

    protected function validateRequest(Request $request)
    {
        $request->validate([
            'limit' => 'integer|min:1'
        ]);
    }

    protected function getBanners(Request $request)
    {
        $query = Banner::query()->latest();

        return $request->has('limit')
            ? $query->limit($request->integer('limit'))->get()
            : $query->get();
    }
}
