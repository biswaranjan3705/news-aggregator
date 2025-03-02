<?php
namespace App\Http\Controllers;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="User Preferences",
 *     description="Manage user preferences"
 * )
 */
class UserPreferenceController extends Controller
{
        /**
     * @OA\Post(
     *     path="/preferences",
     *     summary="Set user preferences",
     *     description="Allows users to set their preferences.",
     *     tags={"User Preferences"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string"), example={"Technology", "Sports"}),
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string"), example={"CNN", "BBC"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="Preferences updated successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function setPreferences(Request $request)
    {
        $request->validate([
            'sources' => 'array',
            'categories' => 'array',
            'authors' => 'array',
        ]);

        $user = Auth::user();
        $preferences = UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            ['sources' => $request->sources, 'categories' => $request->categories, 'authors' => $request->authors]
        );

        return response()->json(['message' => 'Preferences saved!', 'preferences' => $preferences]);
    }

    /**
     * @OA\Get(
     *     path="/preferences",
     *     summary="Get user preferences",
     *     description="Retrieve the user's saved preferences.",
     *     tags={"User Preferences"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Preferences retrieved successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getPreferences()
    {
        $preferences = Auth::user()->preferences;
        return response()->json($preferences);
    }

    /**
     * @OA\Get(
     *     path="/personalized-feed",
     *     summary="Get personalized news feed",
     *     description="Fetch a personalized feed based on user preferences.",
     *     tags={"User Preferences"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Feed retrieved successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function personalizedFeed()
    {
        $user = Auth::user();
        $preferences = $user->preferences;

        $query = Article::query();

        if ($preferences) {
            if ($preferences->sources) {
                $query->whereIn('source', $preferences->sources);
            }
            if ($preferences->categories) {
                $query->whereIn('category', $preferences->categories);
            }
            if ($preferences->authors) {
                $query->whereIn('author', $preferences->authors);
            }
        }

        return response()->json($query->paginate(10));
    }
}
