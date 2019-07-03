<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class OrgDesignation extends Model
{
    const slug          = 'slug';
    const org_id        = 'org_id';
    const name          = 'name';
    const description   = 'description';

    const table       = 'org_designation';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = OrgDesignation::table;

    protected $fillable = [OrgDesignation::name, OrgDesignation::description];
}
