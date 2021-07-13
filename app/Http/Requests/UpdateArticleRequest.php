<?php

namespace App\Http\Requests;

use App\Models\Article;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateArticleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('article_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'publish_at' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'category_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
