import { Component, OnInit } from '@angular/core';
import { CalendarDataService} from '../shared/services/calendar-data.service';

@Component({
  selector: 'app-calendar',
  templateUrl: './calendar.component.html',
  styleUrls: ['./calendar.component.scss']
})
export class CalendarComponent implements OnInit {

  constructor(
    public calendarDataService : CalendarDataService
  ) { }

  ngOnInit() {
  }
  newPop() : void{
    this.calendarDataService.createPopup.showPopup = true;
  }
}
