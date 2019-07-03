<?php

namespace Modules\DriveManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class DriveType extends Model
{
    const slug           = 'drive_type_slug';
    const name           = 'drive_type_name';
    const display_name   = 'drive_type_display';

    const my_drive       = 'my_drive';
    const company_drive  = 'company_drive';
    const shared_me      = 'shared_me';
    const trash          = 'trash';


    const table     = 'dm_drive_type';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = DriveType::table;

    protected $fillable = [

    ];
}
