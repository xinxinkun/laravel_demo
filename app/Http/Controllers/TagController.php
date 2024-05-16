<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\IssueTag;
use App\Models\Tag;
use App\Servcies\IssueService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('tag')]
class TagController extends Controller
{
    protected $issueService;
    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
    }

    #[Post('add', 'tag.add', ['auth:sanctum'])]
    public function add(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|max:255',
            'issue_id' => 'required|exists:issues,id',
        ]);
        $this->issueService->validateUser($request, $validateData['issue_id']);
        DB::transaction(function () use ($validateData) {
            $tag = Tag::create([
                'name' => $validateData['name']
            ]);

            IssueTag::create([
                'issue_id' => $validateData['issue_id'],
                'tag_id' => $tag->id
            ]);
        },3);
        return new ApiResponse(200,'Tag added successfully');
    }

}
