import { Injectable } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Router } from '@angular/router';
import { CookieService } from 'ngx-cookie-service';
import "rxjs/add/operator/share";
import { ToastService } from '../shared/services/toast.service'
import { TimeReportApiService } from '../shared/services/time-report-api.service'
import { TimeReportDataService } from '../shared/services/time-report-data.service'


@Injectable()
export class TimeReportSandbox {

  constructor(
    private toastService:ToastService,
    private timeReportApiService:TimeReportApiService,
    private timeReportDataService:TimeReportDataService,
    private spinner: Ng4LoadingSpinnerService
  ) { }

  /* Sandbox to handle API call for getting work time report[Start] */
  getWorktimeReport() {
    this.spinner.show();
    //Accessing time and report API service
    return this.timeReportApiService.getWorkTime().subscribe((result: any) => {
      this.timeReportDataService.workTimeReport = result.data;
      
      this.timeReportDataService.workTimeFilterPopup.show = false;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting work time report[End] */

   

  /* Sandbox to handle API call for getting work report[Start] */
  getWorkReport() {
    this.spinner.show();
    //Accessing time and report API service
    return this.timeReportApiService.getWorkReport().subscribe((result: any) => {
      this.timeReportDataService.workReportData.workReport = result.data.workReport;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting work time report[End] */

  /* Sandbox to handle API call for seletcted report details[Start] */
  getSelectedReportDetails() {
    this.spinner.show();
    //Accessing time and report API service
    return this.timeReportApiService.getSelectedReportDetails().subscribe((result: any) => {
      this.timeReportDataService.selectedWorkTimeDetails = result.data;
      console.log('imagetttt',this.timeReportDataService.selectedWorkTimeDetails)
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for seletcted report details[End] */

  /* Sandbox to handle API call for details of a selcted report [Start] */
  getDetailedWorkReport() {
    this.spinner.show();
    //Accessing time and report API service
    return this.timeReportApiService.getDetailedWorkReport().subscribe((result: any) => {
      this.timeReportDataService.selectedWorkReportDetails = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /*  Sandbox to handle API call for details of a selcted report [End] */

  /* Sandbox to handle API call for get all departments [Start] */
  getAllDepts() {
    this.spinner.show();
    //Accessing time and report API service
    return this.timeReportApiService.getAllDepts().subscribe((result: any) => {
      this.timeReportDataService.departmentLists = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /*  Sandbox to handle API call for get all departments [End] */

  /* Sandbox to handle API call for confirm daily[Start] */
  confirmDailyReport() {
    this.spinner.show();
    //Accessing time and report API service
    return this.timeReportApiService.confirmDailyReport().subscribe((result: any) => {
      this.timeReportDataService.workTimeDetails.show = false;
      this.getWorktimeReport();
      this.toastService.Success('',result.data.message);
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error('',err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for confirm daily report[End] */

  /* Sandbox to handle API call for confirm daily[Start] */
  confirmReport() {
    this.spinner.show();
    //Accessing time and report API service
    return this.timeReportApiService.confirmReport().subscribe((result: any) => {
      this.timeReportDataService.workReportDetails.show = false;

      // this.getWorktimeReport();
      this.toastService.Success(result.data.msg);
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error('',err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for confirm daily report[End] */

    /* Sandbox to handle API call for Initiate work report[Start] */
    initiateworkReport() {
      return this.timeReportApiService.initiateworkReport().subscribe((result: any) => {
       if (result.status === 'OK') {
           this.timeReportDataService.clockReportdata =  result.data;
           for(let i = 0; i< result.data.tasks.length; i++){
             if(result.data.tasks[i].isChecked === 'true'){
              this.timeReportDataService.sendReportInput.tasks.push(result.data.tasks[i].slug)
             }
           }
       }
       this.spinner.hide();
     },
       err => {
         this.toastService.Error(err.msg)
         this.spinner.hide(); 
      })
   }
  /* Sandbox to handle API call for Initiate work report[End] */
  
  /* Sandbox to handle API gettting tasks for report[Start] */
  getTasksForReport() {
      return this.timeReportApiService.getTasksForReport().subscribe((result: any) => {
  
       if (result.status === 'OK') {
           this.timeReportDataService.tasksClockReport =  result.data;
       }
       this.spinner.hide();
     },
       err => {
         this.toastService.Error('', err.msg)
         this.spinner.hide(); 
      })
   }
  /*Sandbox to handle API gettting tasks for report[End] */

  /* Sandbox to handle API gettting events for report[Start] */
  getEventsForReport() {
      return this.timeReportApiService.getEventsForReport().subscribe((result: any) => {
  
       if (result.status === 'OK') {
           this.timeReportDataService.eventsClockReport =  result.data;
       }
       this.spinner.hide();
     },
       err => {   
         this.toastService.Error('', err.msg)
         this.spinner.hide(); 
      })
   }
  /*Sandbox to handle API gettting events for report[End] */

  /* Sandbox to handle API sendMonthlyReport[Start] */
  sendMonthlyReport() {
      return this.timeReportApiService.sendMonthlyReport().subscribe((result: any) => {
  
      //  if (result.status === 'OK') {
      //    this.toastService.Success(result.data.message);
      //    this.timeReportDataService.createReport.show =  false;
      //    this.timeReportDataService.resetSendReport();
      //  }
      this.timeReportDataService.resetSendReport();
      this.timeReportDataService.createReport.show =  false;
      this.toastService.Success(result.data.msg);
      this.initiateworkReport();
      this.spinner.hide();
     },
       err => {
         this.toastService.Error(err.msg)
         this.spinner.hide(); 
      })
   }
 /* Sandbox to handle API sendMonthlyReport[End] */

}
