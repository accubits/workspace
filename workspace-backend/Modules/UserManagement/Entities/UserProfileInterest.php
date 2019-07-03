<?php

namespace Modules\UserManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserProfileInterest extends Model
{ 
    const table = 'um_user_profile_interest';

    const slug                     = 'slug';
    const user_profile_id          = 'user_profile_id';
    const user_interest_id         = 'user_interest_id';

    protected $table = UserProfileInterest::table;

    protected $fillable = ['slug','user_profile_id', 'user_interest_id'];

    protected $primaryKey = ['user_profile_id', 'user_interest_id'];
    public $incrementing = false;

    protected function setKeysForSaveQuery(Builder $query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

        protected function getKeyForSaveQuery($keyName = null)
        {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
        }

}
