import { Injectable } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Router } from '@angular/router';
import { CookieService } from 'ngx-cookie-service';
import "rxjs/add/operator/share";
import { ToastService } from '../shared/services/toast.service'
import { TimeReportApiService } from '../shared/services/time-report-api.service'

@Injectable()
export class TimeAndReportSandbox {

  constructor(
    private toastService:ToastService,
    private timeReportApiService:TimeReportApiService,
    private spinner: Ng4LoadingSpinnerService
  ) { }

 
  /* Sandbox to handle API call for getting work time report[Start] */
  getWorktimeReport() {
    this.spinner.show();
    //Accessing time and report API service
    return this.timeReportApiService.getWorkTime().subscribe((result: any) => {
     // this.timeReportApiService.responsiblePersons.list = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting work time report[End] */


}
