<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class UserProfileAddress extends Model
{

    const table = 'um_user_profile_address';

    const street_address      = 'street_address';
    const address_line2       = 'address_line2';
    const city                = 'city';
    const state               = 'state';
    const country_id          = 'country_id';
    const zip_code            = 'zip_code';


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = UserProfileAddress::table;

}
