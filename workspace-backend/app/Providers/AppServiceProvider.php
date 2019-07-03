<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        DB::query()->macro('firstOrFail', function () {
            $record = $this->first();
            if ($record) {
                return $record;
            }
            throw new ModelNotFoundException('No records found');
        });

        Validator::extend('after_current_utc', function ($attribute, $value, $parameters, $validator) {
            $currentTimeStamp = Carbon::parse(now())->timestamp;
            //echo 'current_time:'.$currentTimeStamp.'reminder:'. $value;die;
            if ($value <= $currentTimeStamp)
                return false;

            return true;
        });

        Validator::extend('before_twenty_mins_end_date_utc', function ($attribute, $value, $parameters, $validator) {

            $reminderDateTime = Carbon::createFromTimestamp($value);
            $endDateTime      = Carbon::createFromTimestamp($parameters[0]);
            //dd($endDateTime->diffInMinutes($reminderDateTime));

            if (($reminderDateTime < $endDateTime) && ($endDateTime->diffInMinutes($reminderDateTime) >= 20)) {
                return true;
            }

            return false;
        });

        Validator::extend('compare_start_end_date', function ($attribute, $value, $parameters, $validator) {
            if ($value <= $parameters[0])
                return false;

            return true;
        });

        Validator::extend('end_date_twenty_mins_after_start_date_utc', function ($attribute, $value, $parameters, $validator) {
            $endDateTs   = Carbon::createFromTimestamp($value);
            $startDateTs = Carbon::createFromTimestamp($parameters[0]);


            if (($endDateTs->isSameDay($startDateTs) && $endDateTs->isSameMonth($startDateTs) && $endDateTs->isSameYear($startDateTs)) &&
                ($endDateTs->diffInMinutes($startDateTs) <= 20)) {
                return false;
            }

            return true;
        });

        //file upload validation
        Validator::extend('mime_type_validation', function ($attribute, $value, $parameters, $validator) {

            $extensions = ['exe','php','pif','application','gadget','msi','msi','com',
                'scr','hta','cpl','msc','jar','bat','cmd','vb','vbs','vbe','js','jse',
                'ws','wsf','PS1','PS1XML','PS2','PS2XML','PSC1','MSH','MSH1','MSH2',
                'MSHXML','MSH1XML','MSH2XML','PSC2','scf','lnk','inf','reg'
            ];

            if (in_array($value->getClientOriginalExtension(), $extensions)) {
                return false;
            }

            return true;
            /*dd($value->getMimeType());*/
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
