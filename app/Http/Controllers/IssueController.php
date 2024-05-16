<?php

namespace App\Http\Controllers;

use App\Http\Exceptions\AuthException;
use App\Http\Responses\ApiResponse;
use App\Models\Issue;
use App\Models\IssueUser;
use App\Models\Tag;
use App\Servcies\IssueService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('issue')]
class IssueController extends Controller
{
    protected $issueService;

    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
    }

    #[Post('create', 'issue.create', ['auth:sanctum'])]
    public function create(Request $request)
    {
        $validateData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);
        $validateData['creator_id'] = $request->user()->id;
        $validateData['is_open'] = true;
        $issue = Issue::create($validateData);
        return response()->json(['issue' => $issue]);
    }

    #[Post('assign/{issue}', 'issue.assign', ['auth:sanctum'])]
    public function assign(Request $request, Issue $issue)
    {
        Log::info($issue);
        $this->validateIssueOwner($request, $issue);
        $assignee_ids = explode(',', $request->assignee_id);
        $request->merge(['assignee_id' => $assignee_ids]);
        $request->validate([
            'assignee_id' => 'required|array',
            'assignee_id.*' => 'exists:users,id',
        ]);

        foreach ($assignee_ids as $assignee_id) {
            IssueUser::firstOrCreate([
                'issue_id' => $issue->id,
                'user_id' => $assignee_id,
            ]);
        }
        return new ApiResponse(200, 'Assignee successfully');
    }

    #[Post('open/{issue}', 'issue.open', ['auth:sanctum'])]
    public function open(Request $request, Issue $issue)
    {
        $this->issueService->validateUser($request, $issue->id);
        if ($issue->is_open) {
            return new ApiResponse(200, 'Issue is already open');
        }
        $issue->update(['is_open' => true]);
        return new ApiResponse(200, 'Issue opened successfully');

    }

    #[Post('close/{issue}', 'issue.close', ['auth:sanctum'])]
    public function close($request, $issue)
    {
        $this->issueService->validateUser($request, $issue->id);
        if (!$issue->is_open) {
            return new ApiResponse(200, 'Issue is already close');
        }
        $issue->update(['is_open' => false]);
        return new ApiResponse(200, 'Issue close successfully');
    }

    #[Post('list', 'issue.list', ['auth:sanctum'])]
    public function list(Request $request)
    {
        $tags = $request->input('tags');

        $userId = $request->user()->id;
        $perPage = $request->get('per_page', 10);
        $query = Issue::with(['comments:id,content,issue_id', 'tags'])
            ->where(function ($query) use ($userId) {
                $query->where('creator_id', $userId)->orWhereHas('issueUsers', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                });
            })
            ->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            });
        return $query->toSql();
        $issues = $query->paginate($perPage);
        return response()->json(['issues' => $issues]);
    }

    private function validateIssueOwner(Request $request, Issue $issue)
    {
        if ($issue->creator_id !== $request->user()->id) {
            throw new AuthException(1, 'You are not the owner of this issue');
        }
    }
}
