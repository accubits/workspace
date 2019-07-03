<?php

namespace Modules\UserManagement\Entities;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\OrgManagement\Entities\OrgEmployee;
use Modules\PartnerManagement\Entities\Partner;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, Notifiable;

    const id       = 'id';
    const slug     = 'slug';
    const name     = 'name';
    const email    = 'email';
    const password = 'password';
    const verified = 'verified';

    const table = 'um_users';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = User::table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $guard_name = 'web';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'slug'
    ];

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function authAcessToken()
    {
        return $this->hasMany('Modules\UserManagement\Entities\OauthAccessToken');
    }

    public static function generateVerificationCode()
    {
        return str_random(40);
    }


    /**
     * get partner from user
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function partner()
    {
        return $this->hasOne(Partner::class);
    }

    public function orgEmployee()
    {
        return $this->hasOne(OrgEmployee::class);
    }


    /**
     * boot method
     */
    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            $model->slug = uniqid(rand());
		});
    }

}
