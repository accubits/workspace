import { Component, OnInit, ViewChild } from '@angular/core';
import { CalendarComponent } from 'ng-fullcalendar';
import { Options } from 'fullcalendar';
import { CalendarDataService } from '../../shared/services/calendar-data.service';
import { CalendarSandbox } from '../calendar.sandbox'


@Component({
  selector: 'app-calendar-sidebar',
  templateUrl: './calendar-sidebar.component.html',
  styleUrls: ['./calendar-sidebar.component.scss']
})
export class CalendarSidebarComponent implements OnInit {
  calendarSelectShow : boolean = false;
  calendarOptions: Options;
  
  
  @ViewChild(CalendarComponent) ucCalendar: CalendarComponent;

  constructor(
    public calendarDataService: CalendarDataService,
    public calendarSandbox: CalendarSandbox,
  ) { }

  ngOnInit() {
    this.calendarOptions = {
        editable: true,
        eventLimit: false,
        header: {
          left: 'title',
          center: 'prev,next today',
          right: ''
        },
        events: null
      };
  }

  calendarSelect() : void{
    this.calendarSelectShow = !this.calendarSelectShow;
  }
  clickButton(event):void{
    //console.log(event.data.toDate());
    let d = new Date(event.data.toDate());
    // console.log(d.getMonth()+1);
    // console.log(d.getFullYear());
    this.calendarDataService.getCalendarInput.month = d.getMonth()+1;
    //this.calendarDataService.getCalendarInput.type = "month";
    this.calendarDataService.getCalendarInput.year = d.getFullYear().toString();
    this.calendarSandbox.getCalendarDetails() // Getting calendar details
  }
  eventDrop(event):void{
     console.log(event);
     alert("bb");
  }
}
