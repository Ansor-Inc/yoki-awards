<?php

namespace Modules\Book\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookCommentRequest extends FormRequest
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
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'reply_id' => ['sometimes', 'exists:App\Models\Comment,id']
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
