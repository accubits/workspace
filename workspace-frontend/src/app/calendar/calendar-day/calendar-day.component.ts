import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { CalendarDataService } from '../../shared/services/calendar-data.service';
import { CalendarSandbox } from '../calendar.sandbox'

@Component({
  selector: 'app-calendar-day',
  templateUrl: './calendar-day.component.html',
  styleUrls: ['./calendar-day.component.scss']
})
export class CalendarDayComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  eventShowPopup : boolean = false
  legendView : boolean =  false;
  showOverlay : boolean = false;
  selectBox : boolean = false;

  constructor(
     public calendarDataService : CalendarDataService,
     public calendarSandbox: CalendarSandbox,
  ) { }

  ngOnInit() {
    let d = new Date();
    // alert(d.getTimezoneOffset());
    //alert(Intl.DateTimeFormat().resolvedOptions().timeZone);
    // d.getTimezoneOffset();
    // console.log(d.getDay()-1);
    this.calendarDataService.getCalendarInput.day = d.getDate();
    this.calendarDataService.getCalendarInput.month = d.getMonth()+1;
    this.calendarDataService.getCalendarInput.year = d.getFullYear().toString();
    this.calendarDataService.getCalendarInput.type = 'day';
    this.calendarDataService.getCalendarInput.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    this.calendarSandbox.getCalendarDetails() // Getting calendar details
  }

  
  closeOverlay(){ 
    this.showOverlay = false;
    this.legendView = false;
    this.selectBox = false;
  }
  showOverlayPop(popUpToShow){
    this.showOverlay = true;
    this[popUpToShow] = true ;
  }
  closePop(popUpToClose){
    this[popUpToClose] = false;
    this.showOverlay = false;
  }
}