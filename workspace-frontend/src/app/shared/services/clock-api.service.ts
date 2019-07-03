import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Observable } from "rxjs/Observable";
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';
import { Configs } from '../../config';
import { ClockDataService } from './clock.data.service';
import { UtilityService } from './utility.service';
import { CookieService } from 'ngx-cookie-service';


@Injectable()
export class ClockApiService {

  constructor(
    private clockDataService:ClockDataService,
    private utilityService:UtilityService,
    private cookieService: CookieService,
    private http: HttpClient,
  ) { }


  /* get current clock status [Start] */
  getCurrentClockStatus(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'hrm/get-current-clock-status'
    let data = this.clockDataService.clockCurrentstatus;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
  /* get current clock status [End] */

   /* get current clock status [Start] */
  getClockStatus(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'hrm/clock-status'
    let data = this.clockDataService.clockInput;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
   /* get current clock status [End] */

   /* get current clock status [Start] */
  clockOutPreviosDay(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'hrm/clockout-previous-day'
    let data = this.clockDataService.previosDayInput;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
   /* get current clock status [End] */

   /* update work hours [Start] */
  updateWorkHours(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'hrm/save-worktime'
    let data = this.clockDataService.editWorkTime;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
     /* update work hours [Start] */

   /* fetch work hours [Start] */
  fetchWorkDay(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'hrm/fetch-workday'
    let data = this.clockDataService.fetchWorkDayInput;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
     /* fetch work hours [Start] */

   /* initiate work Report[Start] */
  initiateworkReport(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'hrm/initiateWorkReportBeforeSubmit'
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "currentDate": this.utilityService.convertToUnix(new Date())
    };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
     /* initiate work Report [Start] */

   /*get tasks for Report[Start] */
   getTasksForReport(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'hrm/listAllTasksForWorkReport'
    let data = this.clockDataService.getTasksClockReportInput;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
     /* get tasks for Report [Start] */

   /*get events for Report[Start] */
   getEventsForReport(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'hrm/listAllEventsForWorkReport'
    let data = this.clockDataService.getEventsClockReportInput;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
     /* get events for Report [Start] */

   /* Send monthly report[Start] */
   sendMonthlyReport(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'hrm/sendWorkReport'
    let data = this.clockDataService.sendReportInput;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
  /* Send monthly report [Start] */

  /* Generic function to check Responses[Start] */
  checkResponse(response: any) {
    let results = response
    if (results.status) {
      return results;
    }
    else {
      console.log("Error in API");
      return results;
    }
  }
  /* Generic function to check Responses[End] */

}
