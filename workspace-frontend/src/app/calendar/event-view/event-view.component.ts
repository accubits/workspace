import { Component, OnInit } from '@angular/core';
import{ CalendarDataService} from '../../shared/services/calendar-data.service'
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../../activitystream/activity.sandbox';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-event-view',
  templateUrl: './event-view.component.html',
  styleUrls: ['./event-view.component.scss']
})
export class EventViewComponent implements OnInit {
  activeRpTab: string = 'attend';
  attend : string = '';
  invite : string = '';
  decline : string = '';
  eventStatus: boolean;
  userResponse: boolean;
  loggedUser = '';
  
  constructor(
     public calendarDataService : CalendarDataService,
     public actStreamDataService: ActStreamDataService,
     public activitySandboxService: ActivitySandboxService,
     private cookieService: CookieService
  ) { }

  ngOnInit() {
    this.loggedUser = this.cookieService.get('userSlug');
    if (this.actStreamDataService.createEvent.userResponse === false) {
      this.userResponse = false;
    }
    else {
      this.userResponse = true;
    }
  }
   closePop() : void{
     this.calendarDataService.eventModel.showPopup = false;
   }
}
