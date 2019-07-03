import { Component, OnInit } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { TimeDateService } from '../../shared/services/time-date.service';

@Component({
  selector: 'app-greeting-widget',
  templateUrl: './greeting-widget.component.html',
  styleUrls: ['./greeting-widget.component.scss']
})
export class GreetingWidgetComponent implements OnInit {

  date: any;
  user: string;
  greet: string;
  constructor(
    public cookieService: CookieService,
    public timeDateService: TimeDateService
  ) { }

  ngOnInit() {
    this.date = this.timeDateService.getDate();
    this.user = this.cookieService.get('userName');
    this.greet = this.timeDateService.getGreeting();
  }
}
