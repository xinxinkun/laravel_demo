<?php

namespace App\Http\Controllers;

use App\Http\Exceptions\AuthException;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\IssueUser;
use App\Servcies\IssueService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('comment')]
class CommentController extends Controller
{
    protected $issueService;

    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
    }

    #[Post('add', 'comment.add', ['auth:sanctum'])]
    public function addComment(Request $request)
    {
        $validateData = $request->validate([
            'content' => 'required|max:255',
            'issue_id' => 'required|exists:issues,id',
        ]);
        $this->issueService->validateUser($request, $validateData['issue_id']);
        $validateData['user_id'] = $request->user()->id;
        $comment = Comment::create($validateData);
        return response()->json(['comment' => $comment]);
    }


}
