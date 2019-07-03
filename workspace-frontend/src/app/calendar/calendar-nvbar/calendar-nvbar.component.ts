import { Component, OnInit } from '@angular/core';
import { Routes, RouterModule, Router, ActivatedRoute,NavigationEnd ,Event} from '@angular/router';
import { CalendarDataService } from '../../shared/services/calendar-data.service';
import { CalendarSandbox } from '../calendar.sandbox'

@Component({
  selector: 'app-calendar-nvbar',
  templateUrl: './calendar-nvbar.component.html',
  styleUrls: ['./calendar-nvbar.component.scss']
})
export class CalendarNvbarComponent implements OnInit {
  legendView:boolean = false;
  typeSelect:boolean = false;
  showOverlay:boolean = false;

  
  constructor(
    public router: Router,
    public route:ActivatedRoute,
    public calendarDataService: CalendarDataService,
    public calendarSandbox: CalendarSandbox,
  ) { }

  ngOnInit() {
  }
  showLegend(){
    this.legendView = true;
    this.showOverlay = true;
  }
  showType(){
    this.typeSelect = true;
    this.showOverlay = true;
  }
  closeOverlay()
  {
    this.legendView = false;
    this.typeSelect = false;
    this.showOverlay = false;
  }
  // redirectTo(route): void {
  //   this.router.navigate(["../"+route],{ relativeTo: this.route });
  // }
  updateCalendarType(type){
    // alert("sdfsdf");
    let d = new Date();
    this.calendarDataService.getCalendarInput.month = d.getMonth()+1;
    this.calendarDataService.getCalendarInput.year = d.getFullYear().toString();
    this.calendarDataService.getCalendarInput.type = type;
    this.calendarSandbox.getCalendarDetails() // Getting calendar details
  }
}
