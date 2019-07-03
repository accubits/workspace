<?php

namespace Modules\SocialModule\Entities;

use Illuminate\Database\Eloquent\Model;

class SocialLookup extends Model
{
    const title         = 'lookup_title';
    const attribute     = 'lookup_attribute';
    const value         = 'lookup_value';
   
    const table       = 'social_lookup';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = SocialLookup::table;

    protected $fillable = [SocialLookup::title, 
                          SocialLookup::attribute,
                          SocialLookup::value];
   
}
