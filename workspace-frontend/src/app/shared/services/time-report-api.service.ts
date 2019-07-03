import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Observable } from "rxjs/Observable";
import { CookieService } from 'ngx-cookie-service';
import { Routes, RouterModule, Router } from '@angular/router';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';
import 'rxjs/add/observable/throw';
import { Configs } from '../../config';
import { TimeReportDataService } from './time-report-data.service';
import { UtilityService } from './utility.service';




@Injectable()
export class TimeReportApiService {

  constructor(
    private cookieService: CookieService,
    private http: HttpClient,
    private timeReportDataService: TimeReportDataService,
    private utilityService: UtilityService,
  ) { }


  /* Get work time report[Start] */
  getWorkTime(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/time-reports'
    let data = this.timeReportDataService.getWorkTimeInput
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
 }
  /* Get work time report[End] */

  /* Get work time report[Start] */
  getWorkReport(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/fetchall-user-work-reports'
    let data = this.timeReportDataService.getWorkReportInput;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
 }
  /* Get work time report[End] */

  /* Get work time report[Start] */
  getSelectedReportDetails(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/daily-report-details'
    let data = this.timeReportDataService.getSelectedWorkTimeInput;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
 }
  /* Get work time report[End] */

  /* Get Detailed Work Report of a selected report[Start] */
  getDetailedWorkReport(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/fetch-work-report'
    let data = this.timeReportDataService.detailedWorkReportInput;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
 }
  /* Get Detailed Work Report of a selected report[End] */

  /* Get Detailed Work Report for get all depts[Start] */
  getAllDepts(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'orgmanagement/fetchAllDepartments'
    let data =   {
      "orgSlug": this.cookieService.get('orgSlug'),
    };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
 }
  /* Get Detailed Work Report for get all depts[End] */

  /* Confirm Daily report[Start] */
  confirmDailyReport(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/confirm-daily-report'
    let data = {
      "reportSlug": this.timeReportDataService.getSelectedWorkTimeInput.reportSlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
 }
  /* Confirm Daily report[End] */

   /* Confirm Daily report[Start] */
   confirmReport(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/confirmWorkReport'
    let data = {
      "reportSlug": this.timeReportDataService.selectedWorkReportDetails.reportSlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
 }
  /* Confirm Daily report[End] */

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
    let data = this.timeReportDataService.getTasksClockReportInput;
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
    let data = this.timeReportDataService.getEventsClockReportInput;
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
    let data = this.timeReportDataService.sendReportInput;
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
