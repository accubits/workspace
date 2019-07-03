<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HrmAbsentCount extends Model
{
    const absence_id           = 'absence_id';
    const absent_days          = 'absent_days';
    const leave_type_id        = 'leave_type_id';


    const table     = 'hrm_absent_count';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmAbsentCount::table;

    protected $primaryKey = [HrmAbsentCount::absence_id, HrmAbsentCount::absent_days];
    public $incrementing = false;

    protected $fillable = [
        HrmAbsentCount::absence_id,
        HrmAbsentCount::absent_days,
        HrmAbsentCount::leave_type_id
    ];

    public $timestamps = false;



/**
 * Set the keys for a save update query.
 *
 * @param  \Illuminate\Database\Eloquent\Builder  $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
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

/**
 * Get the primary key value for a save query.
 *
 * @param mixed $keyName
 * @return mixed
 */
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
