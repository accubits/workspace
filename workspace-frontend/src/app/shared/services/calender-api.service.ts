import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Observable } from "rxjs/Observable";
import { CookieService } from 'ngx-cookie-service';
import { Routes, RouterModule, Router } from '@angular/router';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';
import 'rxjs/add/observable/throw';
import { Configs } from '../../config';
import { CalendarDataService } from './calendar-data.service';

@Injectable()
export class CalenderApiService {

  constructor(
    private cookieService: CookieService,
    private http: HttpClient,
    private calendarDataService: CalendarDataService
  ) { }

  /* Get Calendar details[Start] */
  getCalendarDetails(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/fetchAllCalender'
    let data = this.calendarDataService.getCalendarInput
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
 }
  /* Get Calendar details[End] */
}
