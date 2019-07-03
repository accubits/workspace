import { Component, OnInit } from '@angular/core';
import { HrmDataService } from '../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';
import { UtilityService } from '../../shared/services/utility.service';

@Component({
  selector: 'app-new-user',
  templateUrl: './new-user.component.html',
  styleUrls: ['./new-user.component.scss']
})
export class NewUserComponent implements OnInit {

  roleShow: boolean = false;
  department: boolean = false;
  selected: string = 'invite';
  isValidated = true;
  isEmailValidated = false;
  isPasswordValidated = false;
  isSubmit = false;
  departList: boolean = false;
  empList = false;
  absencePerson: boolean = false;

  constructor(
    public hrmDataService: HrmDataService,
    public utilityService: UtilityService,
    public hrmSandboxService: HrmSandboxService
  ) { }

  ngOnInit() {
    if(this.hrmDataService.employee.action !== 'update'){
      this.hrmDataService.employee.option = 'invite'
      this.hrmDataService.toUsers.toUsers = [];
      this.hrmDataService.employee.reportManagerEmpSlug = null;
      this.hrmSandboxService.getAllDepartment();
      this.hrmSandboxService.getAllEmployee();
      if (this.hrmDataService.employee.action === 'create') {
        this.hrmDataService.resetEmployee();
      }
      else {
        this.selected = 'register';
      }
      this.hrmSandboxService.getAllDepartment();
    }
    else{
      this.hrmSandboxService.getAllDepartment();
      this.selected = 'register';
    }
  }

  /* handling popups[start] */
  closePop() {
    this.hrmDataService.resetEmployee();
    this.hrmDataService.resetPopUp();
    this.hrmDataService.invitePop.show = false;
  }
  showDepart() {
    this.departList = true;
  }
  hideDepart() {
    this.departList = false;
  }
  showEmpList() {
    this.empList = true;
  }
  hideEmpList() {
    this.empList = false;
  }
  /* handling popups[end] */

  /* select regiser/invite option[start] */
  selectOpt(option) {
    this.selected = option;
    this.isValidated = true;
    this.hrmDataService.resetEmployee();
    this.hrmDataService.toUsers.toUsers = [];
    this.hrmDataService.employee.reportManagerEmpSlug = null;
    if(option === 'register'){
      this.hrmDataService.employee.option = 'register';
    }
    if(option === 'invite'){
      this.hrmDataService.employee.option = 'invite';
    }
  }
  /* select regiser/invite option[end] */

  /* validate email[start] */
  validateEmail() {
    this.isEmailValidated = this.utilityService.validateEmail(this.hrmDataService.employee.email);
    return this.isEmailValidated;
  }
  /* validate email[end] */

  /* validate invite[start] */
  validateInvite(): boolean {
    this.isValidated = true;
    if (!this.hrmDataService.employee.name) this.isValidated = false;
    if (!this.hrmDataService.employee.email) this.isValidated = false;
    return this.isValidated;
  }
  /* validate invite[end] */

  /* invite and register new employee[start] */
  inviteEmp() {
    this.isSubmit = true;
    if (!this.validateInvite()) return;
    if (!this.validateEmail()) return;
    this.hrmSandboxService.inviteEmployee();
  }

  registerEmp() {
    this.isSubmit = true;
    if (!this.validateInvite()) return;
    if (!this.validateEmail()) return;
    this.hrmSandboxService.registerEmployee();
  }
  /* invite and register new employee[end] */

  /* remove department[start] */
  removeDept(dept) {
    let existingDept = this.hrmDataService.departmentList.toDept.filter(
      part => part.departmentSlug === dept.departmentSlug)[0];
    if (existingDept) {
      let idx = this.hrmDataService.departmentList.toDept.indexOf(existingDept);
      if (idx !== -1) this.hrmDataService.departmentList.toDept.splice(idx, 1);
    }
    let Dept = this.hrmDataService.departmentList.list.filter(
      part => part.departmentSlug === dept.departmentSlug)[0];
    let idx = this.hrmDataService.departmentList.list.indexOf(Dept);
    this.hrmDataService.departmentList.list[idx].existing = false;
    let DeptSlug = this.hrmDataService.departmentList.slug.filter(
      part => part === dept.departmentSlug)[0];
    let index = this.hrmDataService.departmentList.slug.indexOf(DeptSlug);
    this.hrmDataService.departmentList.slug.splice(index)
  }
  /* remove department[end] */

  /* Select users list [Start] */
  selectDepartment(dept): void {
    let existingDept = this.hrmDataService.departmentList.toDept.filter(
      part => part.departmentSlug === dept.departmentSlug)[0];
    if (existingDept) {
      return;
    }
    this.hrmDataService.departmentList.toDept.push({
      departmentSlug: dept.departmentSlug,
      departmentName: dept.departmentName
    });
    this.hrmDataService.departmentList.slug.push(
      dept.departmentSlug
    );
    dept.existing = true;
    this.departList = false;
  }
  /* Select users list [End] */

  /* update employee [start] */
  updateEmp() {
    this.hrmSandboxService.updateEmployee();
  }
  /* update employee [end] */

  /* Select users list [Start] */
  selectEmployee(emp): void {
    this.hrmDataService.toUsers.toUsers = [];
    this.hrmDataService.employee.reportManagerEmpSlug = '';
    this.hrmDataService.toUsers.toUsers.push({
      userSlug: emp.userSlug,
      name: emp.employeeName
    });
    this.hrmDataService.employee.reportManagerEmpSlug = emp.employeeSlug;
    this.absencePerson = false;
    this.empList = false;
  }
  /* Select users list [End] */

  /* Remove user from toUser list [Start] */
  removeUsers(user): void {
    let existingUsers = this.hrmDataService.toUsers.toUsers.filter(
      part => part.userSlug === user.userSlug)[0];
    if (existingUsers) {
      let idx = this.hrmDataService.toUsers.toUsers.indexOf(existingUsers);
      if (idx !== -1) this.hrmDataService.toUsers.toUsers.splice(idx, 1);
    }
    let addUser = this.hrmDataService.employeeList.list.filter(
      part => part.slug === user.userSlug)[0];
    let idx = this.hrmDataService.employeeList.list.indexOf(addUser);
    this.hrmDataService.employeeList.list[idx].existing = false;
    this.hrmDataService.employee.reportManagerEmpSlug = null;
  }
  /* Remove user from toUser list[end] */
}
