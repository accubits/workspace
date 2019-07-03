<?php

namespace Modules\PartnerManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class PartnerManager extends Model
{
    const name        = 'partner_manager_name';
    const partner_manager_slug  = 'partner_manager_slug';
    const user_id        = 'user_id';
    const phone          = 'phone';

    const table       = 'pm_partner_manager';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = PartnerManager::table;

    protected $fillable = [
        PartnerManager::name,
        PartnerManager::partner_manager_slug,
        PartnerManager::user_id
    ];
}
