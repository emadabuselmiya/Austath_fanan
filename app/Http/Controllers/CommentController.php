<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{

	public function index($lesson_id)
    {
        $comments = Comment::with('replies', 'user')
            ->where('lesson_id', $lesson_id)
            ->whereNull('parent_id')
            ->get();

        return response()->json($comments);
	}


	public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lesson_id' => 'required|exists:lessons,id',
            'content' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create($validated);

        return response()->json([
            'message' => 'Comment created successfully.',
            'comment' => $comment,
        ], 200);
	}
	public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully.',
        ]);
	}

	public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment->update($validated);

        return response()->json([
            'message' => 'Comment updated successfully.',
            'comment' => $comment,
        ]);
    }
}
