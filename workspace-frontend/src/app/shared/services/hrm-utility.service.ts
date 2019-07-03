import { Injectable } from '@angular/core';
import {HrmDataService} from './hrm-data.service'
@Injectable()
export class HrmUtilityService {

  constructor(
    public hrmDataService: HrmDataService
  ) { }


  /* Managing Employee selection[Start] */
  manageEmployeSelection(isSelcted:boolean,index: number): void {
    // Checking the employee is selected
    if (isSelcted) {
      this.hrmDataService.employeeDetails.selectedEmployeeIds.push(this.hrmDataService.employeeList.list[index].slug);  // Inserting in to selected organisation list
    
    } else {
      let idx = this.hrmDataService.employeeDetails.selectedEmployeeIds.indexOf(this.hrmDataService.employeeList.list[index].slug);
      this.hrmDataService.employeeDetails.selectedEmployeeIds.splice(idx, 1); // Deleting fron slected task list
    }
    this.hrmDataService.employeeDetails.selectedEmployeeIds.length > 0 ? this.hrmDataService.employeeDetails.showPopup = true : this.hrmDataService.employeeDetails.showPopup = false;

  }
  /* Managing Employee selection[End] */
}
