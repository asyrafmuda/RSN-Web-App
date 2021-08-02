<?php

namespace App\Http\Requests;

use App\Models\Banner;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateBannerRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('banner_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
            'description' => [
                'string',
                'nullable',
            ],
            'published_at' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
