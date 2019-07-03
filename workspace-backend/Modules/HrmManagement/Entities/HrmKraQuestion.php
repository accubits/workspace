<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\OrgManagement\Entities\Organization;
use Modules\HrmManagement\Entities\HrmKraModule;
use Modules\UserManagement\Entities\User;
use Modules\HrmManagement\Entities\HrmKraQuestionType;

class HrmKraQuestion extends Model
{
    const slug = 'slug';
    const org_id = 'org_id';
    const kra_module_id = 'kra_module_id';    
    const order_no = 'order_no';
    const kra_question_type_id = 'kra_question_type_id';
    const question = 'question';
    const enable_comment = 'enable_comment';
    const creator_user_id = 'creator_user_id';

    const table       = 'hrm_kra_questions';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmKraQuestion::table;

    protected $fillable = [HrmKraQuestion::slug, HrmKraQuestion::question];
}
