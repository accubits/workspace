import { Component, OnInit } from '@angular/core';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../activity.sandbox';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-act-event-view',
  templateUrl: './act-event-view.component.html',
  styleUrls: ['./act-event-view.component.scss']
})
export class ActEventViewComponent implements OnInit {
  activeRpTab: string = 'attend';
  eventStatus: boolean;
  userResponse: boolean;

  constructor(public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService,
    private cookieService: CookieService) { }

  ngOnInit() {
    this.actStreamDataService.createEvent.loggedUser = this.cookieService.get('userSlug');
   if (this.actStreamDataService.createEvent.userResponse === false) {
      this.userResponse = false;
    }
    else {
      this.userResponse = true;
    }
  }

  /* Update event status[Start] */
  eventStatusUpdate(eventSlug, eventResponse) {
    this.activitySandboxService.eventStatusUpdate(eventSlug, eventResponse);
    this.actStreamDataService.eventStatusPopUp.show = false;
  }

  eventStatusChange() {
    if (this.actStreamDataService.createEvent.eventResponse === 'decline') {
      this.actStreamDataService.eventMessage.msg = 'You are not attending this event';
      this.eventStatus = false;
    }
    if (this.actStreamDataService.createEvent.eventResponse === 'going') {
      this.actStreamDataService.eventMessage.msg = 'You are attending this event';
      this.eventStatus = true;
    }
    this.actStreamDataService.eventStatusPopUp.show = true;
  }
  /* Update event status[end] */

  /* Update event[start] */
  updateEvent() {
    this.actStreamDataService.eventShow.show = false;
    this.actStreamDataService.eventView.show = false;
    this.actStreamDataService.createEvent.action = 'update';
    this.actStreamDataService.selectedWidget.selWctab = 'event';
    this.actStreamDataService.activityCreatePopUp.show = true;
  }
  /* Update event[end] */

  closeEvent() {
    this.actStreamDataService.eventShow.show = false;
    this.actStreamDataService.eventView.show = false;
  }
  hidePop() {
    this.actStreamDataService.eventShow.show = false;

  }
}
