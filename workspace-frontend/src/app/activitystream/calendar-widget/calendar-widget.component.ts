import { Component, OnInit } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { TimeDateService } from '../../shared/services/time-date.service';
import { Configs } from '../../config';

@Component({
  selector: 'app-calendar-widget',
  templateUrl: './calendar-widget.component.html',
  styleUrls: ['./calendar-widget.component.scss']
})
export class CalendarWidgetComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;

  day: string;
  date: any;

  constructor(
    public cookieService: CookieService,
    public timeDateService: TimeDateService
  ) { }

  ngOnInit() {
    this.day = this.timeDateService.getDay();
    this.date = this.timeDateService.getDate();
  }

}
