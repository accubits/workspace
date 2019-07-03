<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{

    const table = 'um_user_profile';

    const first_name        = 'first_name';
    const last_name         = 'last_name';
    const user_id           = 'user_id';
    const user_image        = 'user_image';
    const image_path        = 'image_path';
    const birth_date        = 'birth_date';
    const phone             = 'phone';
    const timezone          = 'timezone';
    const user_profile_address_id    = 'user_profile_address_id';


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = UserProfile::table;


    protected $fillable = ['id', 'first_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
