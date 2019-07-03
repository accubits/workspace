import { Injectable } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Router } from '@angular/router';
import { CookieService } from 'ngx-cookie-service';
import "rxjs/add/operator/share";
import { ToastService } from '../shared/services/toast.service';
import { CalenderApiService } from '../shared/services/calender-api.service';
import { CalendarDataService } from '../shared/services/calendar-data.service'

@Injectable()
export class CalendarSandbox {

  constructor(
    private toastService:ToastService,
    private calenderApiService:CalenderApiService,
    private calendarDataService:CalendarDataService,
    private spinner: Ng4LoadingSpinnerService
  ) { }

  
  /* Sandbox to handle API call for getting work time report[Start] */
  getCalendarDetails() {
    this.spinner.show();
    //Accessing time and report API service
    return this.calenderApiService.getCalendarDetails().subscribe((result: any) => {

      if (this.calendarDataService.getCalendarInput.type === 'month') {
        this.calendarDataService.calenderReport.calendarDetails= result.data;
      }
      else{
         this.calendarDataService.calenderReport.calendar= result.data.calender;
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting work time report[End] */


}
