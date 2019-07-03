<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class SuperadminSettings extends Model
{
    const slug                = 'settings_slug';
    const is_default_dashboard_img = 'is_default_dashboard_img';
    const dashboard_img       = 'dashboard_img';
    const dashboard_img_path  = 'dashboard_img_path';
    const dashboard_msg       = 'dashboard_msg';
    const user_id             = 'user_id';


    const table     = 'superadmin_settings';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SuperadminSettings::table;

    protected $fillable = [
        SuperadminSettings::slug,
        SuperadminSettings::dashboard_img,
        SuperadminSettings::dashboard_img_path,
        SuperadminSettings::dashboard_msg,
        SuperadminSettings::user_id
    ];
}
