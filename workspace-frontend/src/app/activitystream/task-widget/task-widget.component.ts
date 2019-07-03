import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../activity.sandbox';
import { CookieService } from 'ngx-cookie-service';
import { stringify } from '@angular/core/src/util';

@Component({
  selector: 'app-task-widget',
  templateUrl: './task-widget.component.html',
  styleUrls: ['./task-widget.component.scss']
})
export class TaskWidgetComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
 
  firstchoice:boolean =  false;
  clickStatus:string = 'sevenDays';
  constructor(public actStreamDataService: ActStreamDataService,
              public activitySandboxService: ActivitySandboxService,) { }

  ngOnInit() {
    
    this.activitySandboxService.getTaskWidgetDetails();
  }

   goToTask(): void {
    this.actStreamDataService.showCreatetaskpopup.show = true;
    this.actStreamDataService.activityCreatePopUp.show = true;
  }
   /* Update Task Status-Status [Start]*/
    updateTaskStatus(status): void {
    this.actStreamDataService.taskWidgetDetails.taskDateRange= status;
    this.activitySandboxService.getTaskWidgetDetails();
    this.clickStatus=status;
    this.actStreamDataService.taskWidgetDetails.taskDateRange= status;
  }
  /* Update Task Status-Status [End]*/
}
