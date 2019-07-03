<?php

namespace Modules\HrmManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class HrmLeaveTypeCategory extends Model
{
    const slug            = 'category_slug';
    const category_display_name = 'category_display_name';
    const category_name         = 'category_name';
    const is_active       = 'is_active';



    const table     = 'hrm_leave_type_category';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = HrmLeaveTypeCategory::table;

    protected $fillable = [
        HrmLeaveTypeCategory::slug,
        HrmLeaveTypeCategory::category_display_name,
        HrmLeaveTypeCategory::category_name,
        HrmLeaveTypeCategory::is_active
    ];
}
