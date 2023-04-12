<?php

namespace Modules\Content\Http\Controllers;

use Illuminate\Routing\Controller;

class PopupController extends Controller
{
    public function index()
    {
        return collect([
            'title' => setting('popup_title'),
            'body' => setting('popup_body'),
            'code' => setting('popup_code')
        ]);
    }
}
