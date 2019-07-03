<?php

namespace Modules\DriveManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class DriveContent extends Model
{
    const slug            = 'content_slug';
    const drive_id        = 'drive_id';
    const size            = 'content_size';
    const parent_id       = 'parent_content_id';
    const file_path       = 'file_path';
    const path_enum       = 'path_enum';
    const file_name       = 'file_name';
    const file_extension  = 'file_extension';
    const is_folder       = 'is_folder';
    const is_mydrive      = 'is_mydrive';
    const is_companydrive = 'is_companydrive';
    const is_trashed      = 'is_trashed';
    const creator_id      = 'creator_id';


    const table     = 'dm_drive_content';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = DriveContent::table;

    protected $fillable = [
    ];
}
