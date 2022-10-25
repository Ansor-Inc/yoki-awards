<?php

namespace Modules\Book\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookCommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|string',
            'user_id' => ['required', 'exists:users,id'],
            'reply_id' => ['sometimes', 'exists:comments,id']
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth('sanctum')->user()->id
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('sanctum')->check();
    }
}
