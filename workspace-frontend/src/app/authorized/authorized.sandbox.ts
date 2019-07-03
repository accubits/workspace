import { Injectable } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { Location } from '@angular/common';
import merge from 'deepmerge'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Observable } from 'rxjs/Observable';
import "rxjs/add/operator/share";
import { ToastService } from '../shared/services/toast.service';
import { ClockApiService } from '../shared/services/clock-api.service';
import { ClockDataService } from '../shared/services/clock.data.service';
import { UtilityService } from '../shared/services/utility.service';

@Injectable()
export class AuthorizedSandbox {

  constructor(
    private toastService: ToastService,
    private spinner: Ng4LoadingSpinnerService,
    private clockApiService: ClockApiService,
    private clockDataService: ClockDataService,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private location: Location,
    public utilityService : UtilityService
  ) { }

   
    /* Sandbox to handle API call for getting current clock status[Start] */
    getCurrentClockStatus() {
    //  this.spinner.show();
      return this.clockApiService.getCurrentClockStatus().share()
    }
    /* Sandbox to handle API call for getting current clock status[End] */

    /* Sandbox to handle API call for getting clock status[Start] */
    getClockStatus() {
      this.clockDataService.clockInput.currentTime = this.utilityService.convertToUnix(new Date());
      return this.clockApiService.getClockStatus().share();
    }
    /* Sandbox to handle API call for getting clock status[End] */

    /* Sandbox to handle API call for getting clock status[Start] */
    clockOutPreviosDay() {
       return this.clockApiService.clockOutPreviosDay().share();
    }
    /* Sandbox to handle API call for getting clock status[End] */

    /* Sandbox to handle API call for update work time[Start] */
    updateWorkHours() {
       return this.clockApiService.updateWorkHours().subscribe((result: any) => {
   
        if (result.status === 'OK') {
           this.toastService.Success('', 'Work time updated Succesfully');
           this.clockDataService.clockManagement.editWork =  false;
           this.clockDataService.resetEditWorkTime();
        }
        this.spinner.hide();
      },
        err => {
          this.toastService.Error('', err.msg)
          this.spinner.hide(); 
       })
    }
    /* Sandbox to handle update work time[End] */

    /* Sandbox to handle API call for fetch work day for edit[Start] */
    fetchWorkDay() {
       return this.clockApiService.fetchWorkDay().subscribe((result: any) => {
   
        if (result.status === 'OK') {
          this.clockDataService.editWorkTime.startTime = this.utilityService.convertTolocale(result.data.startTime);
          this.clockDataService.editWorkTime.endTime = this.utilityService.convertTolocale(result.data.endTime);
          this.clockDataService.editWorkTime.break.hour = result.data.break.hour;
          this.clockDataService.editWorkTime.break.minute = result.data.break.minute;
        }
        this.spinner.hide();
      },
        err => {
          this.toastService.Error('', err.msg)
          this.spinner.hide(); 
       })
    }
    /* Sandbox to handle fetch work day for edit[End] */

    /* Sandbox to handle API call for Initiate work report[Start] */
    initiateworkReport() {
       return this.clockApiService.initiateworkReport().share()
    }
   /* Sandbox to handle API call for Initiate work report[End] */
   
   /* Sandbox to handle API gettting tasks for report[Start] */
   getTasksForReport() {
       return this.clockApiService.getTasksForReport().subscribe((result: any) => {
   
        if (result.status === 'OK') {
            this.clockDataService.tasksClockReport =  result.data;
        }
        this.spinner.hide();
      },
        err => {
          this.spinner.hide(); 
       })
    }
   /*Sandbox to handle API gettting tasks for report[End] */

   /* Sandbox to handle API gettting events for report[Start] */
   getEventsForReport() {
       return this.clockApiService.getEventsForReport().subscribe((result: any) => {
   
        if (result.status === 'OK') {
            this.clockDataService.eventsClockReport =  result.data;
        }
        this.spinner.hide();
      },
        err => {
           this.spinner.hide(); 
       })
    }
   /*Sandbox to handle API gettting events for report[End] */

   /* Sandbox to handle API sendMonthlyReport[Start] */
   sendMonthlyReport() {
       return this.clockApiService.sendMonthlyReport().subscribe((result: any) => {
   
        if (result.status === 'OK') {
          this.toastService.Success('', result.data.message);
          this.clockDataService.clockManagement.showWorkReport =  false;
          this.clockDataService.resetSendReport();
        }
        this.spinner.hide();
      },
        err => {
          this.toastService.Error('', err.msg)
          this.spinner.hide(); 
       })
    }
  /* Sandbox to handle API sendMonthlyReport[End] */

}
