<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\Admin\ArticleResource;
use App\Models\Article;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('article_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ArticleResource(Article::with(['category'])->get());
    }

    public function store(StoreArticleRequest $request)
    {
        $article = Article::create($request->all());

        if ($request->input('image', false)) {
            $article->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        if ($request->input('pdf', false)) {
            $article->addMedia(storage_path('tmp/uploads/' . basename($request->input('pdf'))))->toMediaCollection('pdf');
        }

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Article $article)
    {
        abort_if(Gate::denies('article_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ArticleResource($article->load(['category']));
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->update($request->all());

        if ($request->input('image', false)) {
            if (!$article->image || $request->input('image') !== $article->image->file_name) {
                if ($article->image) {
                    $article->image->delete();
                }
                $article->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($article->image) {
            $article->image->delete();
        }

        if ($request->input('pdf', false)) {
            if (!$article->pdf || $request->input('pdf') !== $article->pdf->file_name) {
                if ($article->pdf) {
                    $article->pdf->delete();
                }
                $article->addMedia(storage_path('tmp/uploads/' . basename($request->input('pdf'))))->toMediaCollection('pdf');
            }
        } elseif ($article->pdf) {
            $article->pdf->delete();
        }

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Article $article)
    {
        abort_if(Gate::denies('article_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $article->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
