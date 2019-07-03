<?php

namespace Modules\PartnerManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\OrgManagement\Entities\OrgLicense;
use Modules\UserManagement\Entities\User;

class Partner extends Model
{
    const partner_slug       = 'partner_slug';
    const name               = 'partner_name';
    const user_id            = 'user_id';
    const phone              = 'phone';
    const country_id         = 'country_id';
    const partner_manager_id = 'partner_manager_id';
    const bg_image           = 'bg_image';
    const bg_image_path      = 'bg_image_path';
    const is_bg_default_img  = 'is_bg_default_img';
    const dashboard_msg      = 'dashboard_msg';

    const table              = 'pm_partner';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = Partner::table;

    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orgLicense()
    {
        return $this->hasOne(OrgLicense::class);
    }
}
