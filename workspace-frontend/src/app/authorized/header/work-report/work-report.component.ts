import { Component, OnInit } from '@angular/core';
import { ClockDataService } from '../../../shared/services/clock.data.service';
import { AuthorizedSandbox } from '../../authorized.sandbox';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { ToastService } from '../../../shared/services/toast.service';

@Component({
  selector: 'app-work-report',
  templateUrl: './work-report.component.html',
  styleUrls: ['./work-report.component.scss']
})
export class WorkReportComponent implements OnInit {
  showTaskList:boolean =false;  
  currentDate  = new Date();
  eventTaskList: boolean = false;

  constructor(
    public authorizedSandbox : AuthorizedSandbox,
    public clockDataService : ClockDataService,
    private spinner: Ng4LoadingSpinnerService,
    private toastService: ToastService,
  ) { }

  ngOnInit() {
    this.initiateworkReport();
    this.getTasksForReport();
    this.getEventsForReport();
  }

  closeWorkReport(): void{
    this.clockDataService.clockManagement.showWorkReport = false;
    this.clockDataService.resetSendReport();
    
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
    this.authorizedSandbox.initiateworkReport().subscribe((result: any) => {
   
      if (result.status === 'OK') {
          this.clockDataService.clockReportdata =  result.data;
          for(let i=0;i<this.clockDataService.clockReportdata.tasks.length;i++){
            this.includeTasktoSubmit(this.clockDataService.clockReportdata.tasks[i].isChecked,this.clockDataService.clockReportdata.tasks[i])
          }
          for(let i=0;i<this.clockDataService.clockReportdata.events.length;i++){
            this.includeEventtoSubmit(this.clockDataService.clockReportdata.events[i].isChecked,this.clockDataService.clockReportdata.events[i])   
          }

          this.clockDataService.sendReportInput.reportDescription = this.clockDataService.clockReportdata.report;
      }
      this.spinner.hide();
    },
      err => {
        this.toastService.Error('', err.msg)
        this.spinner.hide(); 
     })
  }

  /* get tasks for report */
  getTasksForReport():void{
    this.authorizedSandbox.getTasksForReport()
  }

  /* get events for report */
  getEventsForReport():void{
    this.authorizedSandbox.getEventsForReport()
  }

  /* Send Monthly report */
  sendMonthlyReport(isSend):void{
    this.clockDataService.sendReportInput.isSend = isSend;
    this.clockDataService.sendReportInput.fromDate = this.clockDataService.clockReportdata.dates.startDate;
    this.clockDataService.sendReportInput.toDate = this.clockDataService.clockReportdata.dates.endDate;
    this.clockDataService.sendReportInput.toUser = this.clockDataService.clockReportdata.to[0].slug;
    this.authorizedSandbox.sendMonthlyReport()
  }

  /* Select a task from list */
  selectTask(seltask):void{
    this.clockDataService.clockReportdata.tasks.push(
      {
        "slug": seltask.slug,
        "title": seltask.title
      }
    )
  } 

  /* Select an event from list */
  selectEvent(selEvent):void{
    this.clockDataService.clockReportdata.events.push(
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
      this.clockDataService.sendReportInput.tasks.push(selTask.slug):
      this.clockDataService.sendReportInput.tasks.splice(this.clockDataService.sendReportInput.tasks.indexOf(selTask.slug),1);
      console.log(this.clockDataService.sendReportInput.tasks) 
    }

  /* Include event for submit */
  includeEventtoSubmit($event,selevent):void{
    $event?
      this.clockDataService.sendReportInput.events.push(selevent.eventSlug):
      this.clockDataService.sendReportInput.events.splice(this.clockDataService.sendReportInput.events.indexOf(selevent.eventSlug),1);
      console.log(this.clockDataService.sendReportInput.tasks) 
    }



}
