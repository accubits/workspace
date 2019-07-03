<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmKraModule extends Model
{
    const slug         = 'slug';
    const org_id        = 'org_id';
    const title         = 'title';
    const description   = 'description';
    
    const creator_user_id    = 'creator_user_id';
    
    const table       = 'hrm_kra_modules';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmKraModule::table;

    protected $fillable = [HrmKraModule::title, HrmKraModule::description];
}
