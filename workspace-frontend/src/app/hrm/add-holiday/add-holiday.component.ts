import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';
import { UtilityService } from '../../shared/services/utility.service';

@Component({
  selector: 'app-add-holiday',
  templateUrl: './add-holiday.component.html',
  styleUrls: ['./add-holiday.component.scss']
})
export class AddHolidayComponent implements OnInit {
  holidayName: boolean = false;
  isValidated = true;
  constructor(public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService,
    private utilityService: UtilityService) { }
  ngOnInit() {
  }

  /* hide holiday popup[Start] */
  hideAddHoliday() {
    this.hrmDataService.addHoliday.show = false;
  }
 /* hide holiday popup[end] */
  
  /* Validating creating new holiday[Start] */
  validateHoliday(): boolean {
    this.isValidated = true;
    if (!this.hrmDataService.holiday.name) this.isValidated = false;
    if (!this.hrmDataService.holiday.holidayDate) this.isValidated = false;
    if (!this.hrmDataService.holiday.info) this.isValidated = false;
    return this.isValidated;
  }
  /* Validating creating new holiday[end] */

   /* creating new holiday[Start] */
  createHoliday() {
    if (!this.validateHoliday()) return;
    this.hrmDataService.holiday.holidayDate = this.utilityService.convertToUnix(this.hrmDataService.holiday.holidayDate);
    this.hrmSandboxService.createHoliday();
  }
   /* creating new holiday[end] */

  /* update holiday[Start] */
  updateHoliday() {
    if (!this.validateHoliday()) return;
    this.hrmDataService.holiday.holidayDate = this.utilityService.convertToUnix(this.hrmDataService.holiday.holidayDate);
    this.hrmSandboxService.createHoliday();
  }
  /* update holiday[end] */

   /* clear date[Start] */
  clearDate(){
    this.hrmDataService.holiday.holidayDate = null;
  }
  /* clear date[end] */
}
