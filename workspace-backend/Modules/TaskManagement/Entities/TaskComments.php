<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskComments extends Model
{

    const slug               = 'slug';
    const description        = 'description';
    const parent_comment_id  = 'parent_comment_id';
    const task_id      = 'task_id';
    const user_id      = 'user_id';


    const table = 'tm_task_comments';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskComments::table;

    protected $fillable = [
        TaskComments::description
    ];

    public function taskCommentsLike()
    {
        return $this->hasMany(TaskCommentsLike::class, TaskCommentsLike::comment_id);
    }
}
