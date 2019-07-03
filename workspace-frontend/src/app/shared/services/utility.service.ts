import { Injectable } from '@angular/core';
import { UUID } from 'angular2-uuid';
import * as _moment from 'moment';
@Injectable()
export class UtilityService {

  constructor() { }

  /* Service to convert date to unix time Stamp */
  convertToUnix(dateTime: any): any {
    if (!dateTime) return null;

    if(dateTime.hasOwnProperty('_isAMomentObject')){
      dateTime = dateTime.toDate();
    }
   

    let utcDateString = dateTime.toUTCString()
    return new Date(utcDateString).getTime() / 1000;
  }

  convertTolocale(timeStamp: any): any {
    if (!timeStamp) return null;
    let utcDateString = new Date(timeStamp * 1000).toUTCString();
    return _moment(utcDateString);
  }

  /* Service to random id */
  generaterandomID(): any {
    let uuid = UUID.UUID();
    return uuid;
  }

  /* Validate  phone number */
  validatePhone(phone): any {
    // const PHONE_REGEXP = /^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/;
    const PHONE_REGEXP = /[^0-9\s]/
    return !PHONE_REGEXP.test(phone) ? false : true;
  }

  /* Validate  phone number */
  validateEmail(email): any {
    const EMAIL_REGEXP = /^[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/;
    return !EMAIL_REGEXP.test(email) ? false : true;
  }

  /* Validate WEBSITE URL */
  validateWebsite(URL): any {
    const WEBSITE_REGEXP = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
    return !WEBSITE_REGEXP.test(URL) ? false : true;
  }

  getTimeDifference(dateTime): any {
    if (!dateTime) return null;
    var d = new Date(dateTime * 1000).getTime();
    var currtime = new Date().getTime();
    let secondDiff = (currtime - d)
    let seconds = Math.floor(secondDiff / 1000 % 60);
    return Math.round(seconds);
  }

  calculateDuaration(pastTime, futureTime): any {
    // get total seconds between the times
    var delta = Math.abs(futureTime - pastTime) / 1000;

    // calculate (and subtract) whole days
    let days = Math.floor(delta / 86400);
    delta -= days * 86400;

    // calculate (and subtract) whole hours
    let hours = Math.floor(delta / 3600) % 24;
    delta -= hours * 3600;

    // calculate (and subtract) whole minutes
    let minutes = Math.floor(delta / 60) % 60;
    delta -= minutes * 60;

    return hours.toString() + ':' + minutes.toString()
  }

  /* Caluclate stopish time from starting time */
  calculateStopishTime(startTime,totaltime):any{
      let stopTime  = new Date(startTime * 1000);
      let strtTime  = new Date(startTime * 1000).getTime();
      let hours = parseInt(totaltime.split(':')[0]);
      let minutes =  parseInt(totaltime.split(':')[1]);
      let seconds =  parseInt(totaltime.split(':')[2]);

      // adding hours and minutes to get stop time
      stopTime.setHours(stopTime.getHours() + hours);
      stopTime.setMinutes(stopTime.getMinutes() + minutes); 
      stopTime.setSeconds(stopTime.getSeconds() + seconds); 
      
      // calculating the number of seconds in bw them
      let d = stopTime.getTime()
      let secondDiff = (d - strtTime)/1000;
      return Math.round(secondDiff);
  }


}
