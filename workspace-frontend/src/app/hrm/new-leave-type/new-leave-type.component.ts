import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-new-leave-type',
  templateUrl: './new-leave-type.component.html',
  styleUrls: ['./new-leave-type.component.scss']
})
export class NewLeaveTypeComponent implements OnInit {
  type = 'applicable';
  isValidated = true;
  empListingdrop = false;

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }

  newReasonList: boolean = false;
  newPeriodList: boolean = false;
  newNameList: boolean = false;
  newNonPeriodList: boolean = false;
  newNonNameList: boolean = false;
  colorPicker: boolean = false;

  ngOnInit() {
    if(this.hrmDataService.leaveType.action === 'update'){
      if (this.hrmDataService.leaveType.isApplicableFor === true) {
        this.type = 'applicable';
      }
      if (this.hrmDataService.leaveType.isApplicableFor === false) {
        this.type = 'notappli';
      }
    }
    if (this.hrmDataService.leaveType.action === 'create') {
      this.hrmDataService.resetLeaveType();
    }
   this.hrmSandboxService.getAllEmployee();
  }

  closeNewLev() {
    this.hrmDataService.resetLeaveType();
    this.hrmSandboxService.getLeaveTypeList();
    this.hrmDataService.newLeavePop.show = false;
  }

  /* Validating creating new message[Start] */
  validateLeaveType(): boolean {
    this.isValidated = true;
    if (!this.hrmDataService.leaveType.name) this.isValidated = false;
    if (!this.hrmDataService.leaveType.description) this.isValidated = false;
    if (this.hrmDataService.leaveType.isApplicableFor && !this.hrmDataService.toUsers.toAllEmployee && this.hrmDataService.toUsers.toUsers.length === 0) this.isValidated = false;
    if (!this.hrmDataService.leaveType.isApplicableFor && this.hrmDataService.toUsers.toUsers.length === 0) this.isValidated = false;
    if (this.hrmDataService.leaveType.isApplicableFor && this.hrmDataService.leaveType.leaveCount === '0') this.isValidated = false;
    if (!this.hrmDataService.leaveType.isApplicableFor && this.hrmDataService.leaveType.leaveCount === '0') this.isValidated = false;
    return this.isValidated;
  }
  /* Validating creating new message[end] */

  createLeaveType() {
    if (!this.validateLeaveType()) return;
    this.hrmSandboxService.createLeaveType();
  }

  /* Get users list [Start] */
  initOrChangeEmpsList(): void {
    this.hrmSandboxService.getAllEmployee();
  }
  /* Get users list [End]*/

  selectType(type) {
    this.hrmDataService.leaveType.resonType = type;
    if (type === 'Paid') {
      this.hrmDataService.leaveType.type = 'paid';
    }
    if (type === 'Un Paid') {
      this.hrmDataService.leaveType.type = 'unpaid';
    }
    if (type === 'On Duty') {
      this.hrmDataService.leaveType.type = 'onduty';
    }
    this.newReasonList = false;
  }

  applicable(type) {
    this.type = type;
    this.hrmDataService.toUsers.toUsers = [];
    this.hrmDataService.toUsers.slug = [];
    for (let i = 0; i < this.hrmDataService.employeeList.list.length; i++) {
      this.hrmDataService.employeeList.list[i].existing = false;
    }
    if (type = 'applicable') {
      this.hrmDataService.toUsers.toAllEmployee = true;
      this.hrmDataService.leaveType.isApplicableFor = true;
     
    }
    if (type = 'notappli') {
      this.hrmDataService.leaveType.isApplicableFor = false;
    }
  }

  showNewReason() {
    this.newReasonList = true;
  }

  hideNewReason() {
    this.newReasonList = false;
  }

  // Applicable popup
  showNewperiod() {
    this.newPeriodList = true;
  }
  hideNewperiod() {
    this.newPeriodList = false;
  }
  // Nonapplicable
  showNonNewperiod() {
    this.newNonPeriodList = true;
  }
  hideNonNewperiod() {
    this.newNonPeriodList = false;
  }
  // Applicable
  showNewName() {
    this.newNameList = true;
  }
  hideNewName() {
    this.newNameList = false;
  }
  // Nonapplicable
  showNonNewName() {
    this.newNonNameList = true;
  }
  hideNonNewName() {
    this.newNonNameList = false;
  }
  showColorPicker() {
    this.colorPicker = true;
  }
  hideColorPicker() {
    this.colorPicker = false;
  }

  /* select all employees[Start] */
  selectAllEmployees(): void {
    this.hrmDataService.toUsers.toAllEmployee = true;
    this.hrmDataService.toUsers.toUsers = [];
    this.newNameList = false;
    this.hrmSandboxService.getAllEmployee();
  }
  /* select all employees[end] */

  /* Select users list [Start] */
  selectEmployee(emp): void {
    let existingUsers = this.hrmDataService.toUsers.toUsers.filter(
      part => part.userSlug === emp.userSlug)[0];
    if (existingUsers) {
      // toast to handle already added participant
      return;
    }
    this.hrmDataService.toUsers.toUsers.push({
      userSlug: emp.userSlug,
      name: emp.employeeName
    });
    this.hrmDataService.toUsers.slug.push(
      emp.userSlug
    );
    emp.existing = true;
    this.hrmDataService.toUsers.toAllEmployee = false;
  }
  /* Select users list [End] */

  /* remove all employees[Start] */
  removeAllEmployees(): void {
    this.hrmDataService.toUsers.toAllEmployee = false;
    this.hrmDataService.toUsers.toUsers = [];
  }
  /* remove all employees[end] */

  /* Remove user from toUser list [Start] */
  removeUsers(user): void {
    let existingUsers = this.hrmDataService.toUsers.toUsers.filter(
      part => part.employeeSlug === user.employeeSlug)[0];
    if (existingUsers) {
      let idx = this.hrmDataService.toUsers.toUsers.indexOf(existingUsers);
      if (idx !== -1) this.hrmDataService.toUsers.toUsers.splice(idx, 1);
    }
    let addUser = this.hrmDataService.employeeList.list.filter(
      part => part.slug === user.employeeSlug)[0];
    let idx = this.hrmDataService.employeeList.list.indexOf(addUser);
    this.hrmDataService.employeeList.list[idx].existing = false
  }
  /* Remove user from toUser list[end] */

  pickColor(colorCode) {
    this.hrmDataService.leaveType.colorCode = colorCode;
    this.colorPicker = false;
  }

  editLeaveType() {
    if (!this.validateLeaveType()) return;
    this.hrmDataService.toUsers.slug = [];
    for(let i = 0; i< this.hrmDataService.toUsers.toUsers.length; i++){
      this.hrmDataService.toUsers.slug.push(this.hrmDataService.toUsers.toUsers[i].userSlug)
    }
    this.hrmSandboxService.createLeaveType();
  }
}
