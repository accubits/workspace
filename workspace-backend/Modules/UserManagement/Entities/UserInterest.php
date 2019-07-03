<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class UserInterest extends Model
{
    const table = 'um_user_interest';

    const user_profile_id = 'user_profile_id';
    const interest_title  = 'interest_title';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = UserInterest::table;


    protected $fillable = [
                            UserInterest::user_profile_id,
                            UserInterest::interest_title,
        
                        ];
}
