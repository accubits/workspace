import { Component, OnInit } from '@angular/core';
import { TimeReportDataService } from '../../shared/services/time-report-data.service';
import { TimeReportSandbox } from '../time-report.sandbox'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { ToastService } from '../../shared/services/toast.service';

import { ClockDataService } from '../../shared/services/clock.data.service';


@Component({
  selector: 'app-create-report',
  templateUrl: './create-report.component.html',
  styleUrls: ['./create-report.component.scss']
})
export class CreateReportComponent implements OnInit {

  showTaskList:boolean =false;  
  currentDate  = new Date();
  eventTaskList: boolean = false;

  constructor(
    public timeReportDataService: TimeReportDataService,
    public timeReportSandbox: TimeReportSandbox,
    private spinner: Ng4LoadingSpinnerService,
    private toastService: ToastService,
    public clockDataService: ClockDataService,

  ) { }

  ngOnInit() {
    this.initiateworkReport();
    this.timeReportSandbox.initiateworkReport();
    // this.getTasksForReport();
    // this.getEventsForReport();
  }

  closeWorkReport(): void{
    this.timeReportDataService.createReport.show = false;
    this.clockDataService.clockStatus.isWorkReportPrompt = false;
    this.timeReportDataService.resetSendReport();
    
  }
  closeList(){
    this.showTaskList = false;
  }

  closeEventList(){
    this.eventTaskList = false;
  }

  eventList(){
    this.eventTaskList = true;
  }
  /* Initiate Work report for submit */
  initiateworkReport():void{
    this.timeReportSandbox.initiateworkReport()
  }

  /* get tasks for report */
  getTasksForReport():void{
    this.timeReportSandbox.getTasksForReport()
  }

  /* get events for report */
  getEventsForReport():void{
    this.timeReportSandbox.getEventsForReport()
  }

  /* Send Monthly report */
  sendMonthlyReport(isSend):void{
    this.timeReportDataService.sendReportInput.isSend = isSend;
    this.timeReportDataService.sendReportInput.fromDate = this.timeReportDataService.clockReportdata.dates.startDate;
    this.timeReportDataService.sendReportInput.toDate = this.timeReportDataService.clockReportdata.dates.endDate;
    this.timeReportDataService.sendReportInput.toUser = this.timeReportDataService.clockReportdata.to[0].slug;
    this.timeReportDataService.sendReportInput.reportDescription = this.timeReportDataService.clockReportdata.report;
    this.timeReportSandbox.sendMonthlyReport()
  }

  /* Select a task from list */
  selectTask(seltask):void{
    this.timeReportDataService.clockReportdata.tasks.push(
      {
        "slug": seltask.slug,
        "title": seltask.title
      }
    )
  } 

  /* Select an event from list */
  selectEvent(selEvent):void{
    this.timeReportDataService.clockReportdata.events.push(
      {
        "eventSlug": selEvent.eventslug,
        "eventTitle": selEvent.eventTitle,
        "eventStartDate": null,
        "eventEndDate": null,
        "location": ""
      }
    )
  }

  /* Include Task for submit */
  includeTasktoSubmit($event,selTask):void{
    $event?
      this.timeReportDataService.sendReportInput.tasks.push(selTask.slug):
      this.timeReportDataService.sendReportInput.tasks.splice(this.timeReportDataService.sendReportInput.tasks.indexOf(selTask.slug),1);
      
    }

   

  /* Include event for submit */
  includeEventtoSubmit($event,selevent):void{
    $event?
      this.timeReportDataService.sendReportInput.events.push(selevent.eventSlug):
      this.timeReportDataService.sendReportInput.events.splice(this.timeReportDataService.sendReportInput.events.indexOf(selevent.eventSlug),1);
     }
}





