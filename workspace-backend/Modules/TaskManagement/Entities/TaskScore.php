<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskScore extends Model
{
    const score_name           = 'score_name';
    const score_display_name   = 'score_display_name';

    const excellence   = 'excellence';
    const positive     = 'positive';
    const negative     = 'negative';

    const table = 'tm_task_score';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskScore::table;

    protected $fillable = [
        TaskScore::score_name,
        TaskScore::score_display_name
    ];

    public $timestamps = false;
}
