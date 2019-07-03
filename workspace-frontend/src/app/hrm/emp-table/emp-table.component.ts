import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmUtilityService } from './../../shared/services/hrm-utility.service';
import { HrmSandboxService } from '../hrm.sandbox';
import { Component, OnInit } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
@Component({
  selector: 'app-emp-table',
  templateUrl: './emp-table.component.html',
  styleUrls: ['./emp-table.component.scss']
})
export class EmpTableComponent implements OnInit {
  checkRole: string;
  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService,
    public hrmUtilityService: HrmUtilityService,
    public cookieService: CookieService
  ) { }
  ngOnInit() {
    this.checkRole = this.cookieService.get('role');
    this.hrmSandboxService.getAllEmployee();
  }
   /* handling popups[start] */
  hideDeptList() {
   this.hrmDataService.resetPopUp();
  }
  showEmpDetail(employeeSlug, userSlug) {
    this.hrmDataService.getEmployeeDetails.userSlug = userSlug;
    this.hrmDataService.employee.empSlug = employeeSlug;
    this.hrmSandboxService.empInformation();
    this.hrmDataService.empDetail.show = true;
  }
  showMoreOption(event, index): void {
    event.stopPropagation();
    this.hrmDataService.optionBtn[index] = true;
  }
  hideMoreOption(event, index) {
    event.stopPropagation();
    this.hrmDataService.optionBtn[index] = false;
  }
  selectEmployees(event): void {
    event.stopPropagation();
    this.hrmDataService.employeeDetails.selectedAll = false;
  }
/* handling popups[end] */

  /* update employee[Start] */
  updateEmployee(event,emp, i) {
    event.stopPropagation();
    this.hrmDataService.optionBtn[i] = false;
    this.hrmDataService.employee.action = 'update';
    this.hrmDataService.employee.empSlug = emp.employeeSlug;
    this.hrmDataService.employee.name = emp.employeeName;
    this.hrmDataService.employee.email = emp.employeeEmail;
    this.hrmDataService.toUsers.toUsers = [];
    if(emp.reportingManagerSlug !== null){
      this.hrmDataService.toUsers.toUsers.push({'slug': emp.reportingManagerSlug, 'name': emp.reportingManagerName})
    }
    this.hrmDataService.employee.reportManagerEmpSlug = emp.reportingManagerSlug;
    for (let i = 0; i < emp.employeeDepartments.length; i++) {
      this.hrmDataService.departmentList.toDept.push({
        departmentSlug: emp.employeeDepartments[i].slug,
        departmentName: emp.employeeDepartments[i].name
      });
    }
    for (let i = 0; i < emp.employeeDepartments.length; i++) {
      this.hrmDataService.departmentList.slug.push(
        emp.employeeDepartments[i].slug
      );
    }
    this.hrmDataService.resetPopUp();
    this.hrmDataService.invitePop.show = true;
  }
  /* update employee[end] */

  /* delete employee[start] */
  deleteEmployee(employeeSlug) {
    this.hrmDataService.employee.empSlug = employeeSlug;
    this.hrmDataService.deletePopUp.show = true;
    this.hrmDataService.deleteMessage.msg = 'Are you sure you want to delete selected employee?';
  }
  conformDelete() {
    this.hrmSandboxService.deleteEmployee();
  }
  /* delete employee[end] */

  /* sort employees by selected option[start]*/
  sortOperation(sortOption) {
    this.hrmDataService.employee.sortOption = sortOption,
      this.hrmDataService.employee.sortMethod === 'asc' ? this.hrmDataService.employee.sortMethod = 'des' : this.hrmDataService.employee.sortMethod = 'asc';
    this.hrmSandboxService.getAllEmployee();
  }
  /* sort employees by selected option[end]*/
}
