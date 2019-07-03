<?php

namespace Modules\OrgManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;
    const name          = 'org_name';
    const slug          = 'org_slug';
    const description   = 'org_description';
    const vertical_id   = 'vertical_id';
    const country_id    = 'country_id';
    const partner_id    = 'partner_id';
    const bg_image      = 'bg_image';
    const bg_image_path = 'bg_image_path';
    const is_bg_default_img = 'is_bg_default_img';
    const dashboard_message      = 'dashboard_message';
    const timezone      = 'timezone';
    const storage       = 'storage';

    const table       = 'org_organization';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = Organization::table;

    protected $dates = ['deleted_at'];

    protected $fillable = [Organization::name, Organization::description];
}
