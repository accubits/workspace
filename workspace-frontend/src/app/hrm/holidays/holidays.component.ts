import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-holidays',
  templateUrl: './holidays.component.html',
  styleUrls: ['./holidays.component.scss']
})
export class HolidaysComponent implements OnInit {
  checkRole:string;
  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService,
    public cookieService: CookieService
  ) { }
  ngOnInit() {
    this.checkRole = this.cookieService.get('role');
    this.hrmSandboxService.getHolidayList();
  }

/* handling popups[start] */
  showAddHoliday(){
    this.hrmDataService.addHoliday.show = true;
  }
/* handling popups[end] */

 /* update holiday[Start] */
  updateHoliday(index, holiday){
    this.hrmDataService.holiday.action = 'update';
    this.hrmDataService.optionBtn[index] = false;
    this.hrmDataService.addHoliday.show = true;
    this.hrmDataService.holiday.holidaySlug = holiday.holidaySlug;
    this.hrmDataService.holiday.name = holiday.name;
    this.hrmDataService.holiday.info = holiday.info;
    this.hrmDataService.holiday.holidayDate = new Date(holiday.date * 1000);
    if(holiday.repeatYearly === true){
      this.hrmDataService.holiday.repeted = 1;   
    }
    else{
      this.hrmDataService.holiday.repeted = 0;
    }
    if(holiday.restricted === true){
      this.hrmDataService.holiday.isRestricted = 1;   
    }
    else{
      this.hrmDataService.holiday.isRestricted = 0;
    }
  }
  /* update holiday[end] */

  /* delete holiday[start] */
  deleteHoliday(holidaySlug){
    this.hrmDataService.deletePopUp.show = true;
    this.hrmDataService.holiday.holidaySlug = holidaySlug
    this.hrmDataService.holiday.action = 'delete';
    this.hrmDataService.deleteMessage.msg = 'Are you sure you want to delete selected Holiday type?'
  }
  conformDeleteHoliday(){
    this.hrmSandboxService.createHoliday();
  }
  /* delete holiday[end] */

    /* sort holiday list by selected option[start]*/
    sortOperation(sortOption) {
      this.hrmDataService.holiday.sortOption = sortOption,
      this.hrmDataService.holiday.sortMethod === 'asc' ? this.hrmDataService.holiday.sortMethod = 'des' : this.hrmDataService.holiday.sortMethod = 'asc';
      this.hrmSandboxService.getHolidayList();
    }
    /* sort holiday list by selected option[end]*/
}
