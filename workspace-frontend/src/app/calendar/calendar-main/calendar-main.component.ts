import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { CalendarDataService } from '../../shared/services/calendar-data.service';
import { CalendarSandbox } from '../calendar.sandbox'
import { ActivitySandboxService } from '../../activitystream/activity.sandbox';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';

@Component({
  selector: 'app-calendar-main',
  templateUrl: './calendar-main.component.html',
  styleUrls: ['./calendar-main.component.scss']
})
export class CalendarMainComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  viewEventShow : boolean = false;
  // eventView : boolean = false;
  viewEvent : boolean = false;
  legendView : boolean =  false;
  selectBox : boolean = false;

  constructor(
    public calendarDataService: CalendarDataService,
    public calendarSandbox: CalendarSandbox,
    public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService
  ) { }

  ngOnInit() {
    let d = new Date();
    this.calendarDataService.getCalendarInput.month = d.getMonth()+1;
    this.calendarDataService.getCalendarInput.year = d.getFullYear().toString();
    this.calendarDataService.getCalendarInput.type = 'month';
    this.calendarSandbox.getCalendarDetails() // Getting calendar details
  }
  closeOverlay(){ 
    this.legendView = false;
    this.viewEvent = false;
    this.selectBox = false;
  }
  showOverlayPop(idx){
    //this.showOverlay[idx] = true;
    this.calendarDataService.calendarReportModel.showEventDetails[idx] = true;

  }
  closePop(popUpToClose){
    this[popUpToClose] = false;
    //this.showOverlay = false;
  }

   /* show event moeoption [Start] */
   showEvent(calendarEvent) {
     console.log(calendarEvent)
     this.calendarDataService.eventModel.showPopup = true;
     this.actStreamDataService.createEvent.eventSlug = calendarEvent.slug;
     this.activitySandboxService.getEventDetails();
   
    // this.actStreamDataService.eventShow[this.index] = true;
    // this.actStreamDataService.eventView.show = true;
  }
  /* show event moeoption [end] */
}
