<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function __construct(
        private ArticleService $articleService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request): ArticleResource
    {
        $user = $request->user();
        $article = $this->articleService->create($request->validated(), $user);
        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Article $article, UpdateArticleRequest $request): ArticleResource
    {
        $article = $this->articleService->update($article, $request->validated());
        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article): Response
    {
        Gate::authorize('delete', $article);
        $this->articleService->delete($article);
        return response()->noContent();
    }
}
