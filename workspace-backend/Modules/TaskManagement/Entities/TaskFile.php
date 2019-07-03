<?php

namespace Modules\TaskManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class TaskFile extends Model
{
    const taskfile_slug   = 'taskfile_slug';
    const task_id     = 'task_id';
    const filename    = 'filename';
    const filesize    = 'size';
    const extension   = 'extension';


    const table = 'tm_task_file';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = TaskFile::table;


    protected $fillable = [
        TaskFile::taskfile_slug,
        TaskFile::task_id,
        TaskFile::filename,
        TaskFile::extension
    ];

}
