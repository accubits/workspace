<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskCommentsLike extends Model
{
    const comment_id  = 'comment_id';
    const like        = 'like';
    const user_id     = 'user_id';


    const table = 'tm_task_comments_like';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskCommentsLike::table;

    protected $fillable = [
        TaskCommentsLike::comment_id,
        TaskCommentsLike::like,
        TaskCommentsLike::user_id
    ];

    public function taskComments()
    {
        return $this->belongsTo(TaskComments::class, TaskCommentsLike::comment_id);
    }
}
