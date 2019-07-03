<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class Vertical extends Model
{
    const slug        = 'slug';
    const name        = 'name';
    const description = 'vertical_desc';
    const is_active   = 'is_active';

    const table       = 'cm_vertical';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = Vertical::table;

    protected $fillable = [];
}
