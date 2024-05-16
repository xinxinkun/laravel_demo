<?php

namespace App\Servcies;

use App\Http\Exceptions\AuthException;
use App\Models\Issue;
use App\Models\IssueUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IssueService
{
    public function validateUser(Request $request, string $issue_id)
    {
        $issue = Issue::find($issue_id);
        Log::info($issue);
        $userIds = IssueUser::where('issue_id', $issue_id)->pluck('user_id')->toArray();
        array_push($userIds, $issue->creator_id);
        Log::info($userIds);
        Log::info("当前操作用户 ".$request->user()->id);
        if (!in_array($request->user()->id, $userIds)) {
            throw new AuthException(1, 'You are not the owner of this issue');
        }
    }
}
