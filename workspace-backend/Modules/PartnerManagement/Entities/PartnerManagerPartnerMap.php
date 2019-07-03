<?php

namespace Modules\PartnerManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class PartnerManagerPartnerMap extends Model
{
    const partner_manager_id   = 'partner_manager_id';
    const partner_id           = 'partner_id';

    const table       = 'partner_manager_partner_mapping';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = PartnerManagerPartnerMap::table;

    protected $fillable = [PartnerManagerPartnerMap::partner_manager_id,
        PartnerManagerPartnerMap::partner_id];

    public $timestamps = false;
}
