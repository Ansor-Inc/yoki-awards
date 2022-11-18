<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;

class MakePurchaseRequest extends FormRequest
{
    public function authorize()
    {
        if (is_null($this->book->price) && !$this->book->is_free) {
            return Response::deny('This book does not have price!');
        }

        return true;
    }

    public function rules()
    {
        return [
            'phone' => 'required|string'
        ];
    }
}