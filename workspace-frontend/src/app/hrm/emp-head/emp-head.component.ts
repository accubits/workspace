import { Component, OnInit } from '@angular/core';
import { HrmDataService} from '../../shared/services/hrm-data.service';
import { HrmUtilityService } from './../../shared/services/hrm-utility.service';
import { CookieService } from 'ngx-cookie-service';
@Component({
  selector: 'app-emp-head',
  templateUrl: './emp-head.component.html',
  styleUrls: ['./emp-head.component.scss']
})
export class EmpHeadComponent implements OnInit {
  newpop: boolean = false;
  checkRole:string; 
  constructor(
    public hrmDataService : HrmDataService,
    public cookieService: CookieService,
    public hrmUtilityService: HrmUtilityService
  ) { }

  ngOnInit() {
    this.checkRole= this.cookieService.get('role');
  }

   /* handle PopUps [Start] */
  showPop(){
    this.newpop =! this.newpop;
  }
  closePop(){
    this.newpop = false;
  }
  invite(){
    this.newpop = false;
    this.hrmDataService.invitePop.show = true;
  }
  dept(){
    this.hrmDataService.deptPop.show = true;
  }
 /* handle PopUps [end] */

 /* Checking all employees[Start] */
 checkAllEmployees($event): void {
  this.hrmDataService.employeeDetails.selectedEmployeeIds = [];
  for (let i = 0; i < this.hrmDataService.employeeList.list.length; i++) {
    this.hrmDataService.employeeList.list[i].selected = this.hrmDataService.employeeDetails.selectedAll;
    this.hrmUtilityService.manageEmployeSelection(this.hrmDataService.employeeList.list[i].selected, i)
  }
}
/* Checking all employees[end] */
}
