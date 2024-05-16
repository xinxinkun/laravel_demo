<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'creator_id',
        'is_open',
    ];

    public function issueUsers()
    {
        return $this->hasMany(IssueUser::class);
    }

    public function issueTags()
    {
        return $this->hasMany(IssueTag::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'issue_tags');
    }
}
