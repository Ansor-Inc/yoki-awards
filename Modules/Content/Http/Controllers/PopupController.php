<?php

namespace Modules\Content\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Content\Transformers\PopupResource;

class PopupController extends Controller
{
    public function index()
    {
        return PopupResource::make([
            'title' => setting('popup_title'),
            'body' => setting('popup_body'),
            'code' => setting('popup_code')
        ]);
    }
}
