import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { CalendarDataService } from '../../shared/services/calendar-data.service';
import { CalendarSandbox } from '../calendar.sandbox'

@Component({
  selector: 'app-calendar-week',
  templateUrl: './calendar-week.component.html',
  styleUrls: ['./calendar-week.component.scss']
})
export class CalendarWeekComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  legendView : boolean =  false;
  showOverlay : boolean = false;
  selectBox : boolean = false;

  constructor(
    public calendarDataService: CalendarDataService,
    public calendarSandbox: CalendarSandbox,
  ) { }

  ngOnInit() {
    let d = new Date();
    this.calendarDataService.getCalendarInput.month = d.getMonth()+1;
    this.calendarDataService.getCalendarInput.year = d.getFullYear().toString();
    this.calendarDataService.getCalendarInput.type = 'week';
    this.calendarSandbox.getCalendarDetails() // Getting calendar details
  }

  
  closeOverlay(){ 
    this.showOverlay = false;
    this.legendView = false;
    this.selectBox = false;
  }
  showOverlayPop(idx){
    //this.showOverlay[idx] = true;
    this.calendarDataService.calendarReportModel.showEventDetails[idx] = true;
  }
  closePop(popUpToClose){
    this[popUpToClose] = false;
    this.showOverlay = false;
  }
}
