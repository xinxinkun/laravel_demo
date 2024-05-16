<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueTag extends Model
{
    use HasFactory;
    protected $fillable = [
        'issue_id',
        'tag_id',
    ];
}
