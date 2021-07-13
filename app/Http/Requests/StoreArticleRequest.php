<?php

namespace App\Http\Requests;

use App\Models\Article;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreArticleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('article_create');
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
