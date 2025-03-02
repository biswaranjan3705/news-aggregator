<?php
namespace App\Http\Controllers;
use App\Models\Article;
use Illuminate\Http\Request;
 /**
     * @OA\Tag(
     *     name="Articles",
     *     description="Article management and retrieval"
     * )
     */
class ArticleController extends Controller
{

/**
     * @OA\Get(
     *     path="/articles",
     *     summary="Get a list of articles",
     *     description="Fetch all articles with optional filters.",
     *     tags={"Articles"},
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Parameter(name="keyword", in="query", description="Search keyword", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="date", in="query", description="Filter by date (YYYY-MM-DD)", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="category", in="query", description="Filter by category", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="source", in="query", description="Filter by source", required=false, @OA\Schema(type="string"))
     * )
     */
    public function index(Request $request)
    {
        $query = Article::query();

        // Search by keyword
        if ($request->has('keyword')) {
            $query->where('title', 'like', "%{$request->keyword}%")
                ->orWhere('content', 'like', "%{$request->keyword}%");
        }

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('published_at', $request->date);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by source
        if ($request->has('source')) {
            $query->where('source', $request->source);
        }

        return response()->json($query->paginate(10));
    }

    /**
     * @OA\Get(
     *     path="/articles/{id}",
     *     summary="Get an article by ID",
     *     description="Fetch a single article by ID.",
     *     tags={"Articles"},
     *     @OA\Parameter(name="id", in="path", required=true, description="Article ID", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }
}

