<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    const slug        = 'slug';
    const name        = 'name';
    const timezone   = 'timezone';
    const is_active   = 'is_active';

    const table       = 'cm_country';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = Country::table;

    protected $fillable = [Country::slug, Country::name, Country::is_active, Country::timezone];
}
