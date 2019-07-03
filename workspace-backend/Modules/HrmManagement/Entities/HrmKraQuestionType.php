<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmKraQuestionType extends Model
{

    const type_name         = 'type_name';
    const display_name   = 'display_name';

    const table       = 'hrm_kra_question_types';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmKraQuestionType::table;

    protected $fillable = [HrmKraQuestionType::type_name, HrmKraQuestionType::display_name];
}
