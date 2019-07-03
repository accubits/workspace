import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';
import { UtilityService } from '../../shared/services/utility.service';

@Component({
  selector: 'app-add-new-absence',
  templateUrl: './add-new-absence.component.html',
  styleUrls: ['./add-new-absence.component.scss']
})
export class AddNewAbsenceComponent implements OnInit {
  isValidated = true;
  isDateValidated = true;
  absencePerson: boolean = false;
  absenceList: boolean = false;
  startsOnHalfDay: boolean = false;
  endsOnHalfDay: boolean = false;
  dateValidation: boolean = false; 

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService,
    private utilityService: UtilityService
  ) { }

  ngOnInit() {
    this.hrmSandboxService.getAllEmployee();
    this.hrmSandboxService.getLeaveTypeList();
  }

  /* handle popups [Start] */
  closeNewAbsence() {
    this.hrmDataService.resetAbsense();
    this.hrmDataService.newAbsence.show = false;
  }
  absencePersonShow() {
    this.absencePerson = true;
  }
  absencePersonHide() {
    this.absencePerson = false;
  }
  absenceListShow() {
    this.absenceList = true;
  }
  absenceListHide() {
    this.absenceList = false;
  }
  /* handle popups [end] */

  /* Select users list [Start] */
  selectEmployee(emp): void {
    this.hrmDataService.toUsers.toUsers = [];
    this.hrmDataService.toUsers.slug = [];
    this.hrmDataService.toUsers.toUsers.push({
      userSlug: emp.userSlug,
      name: emp.employeeName
    });
    this.hrmDataService.toUsers.slug.push(
      emp.userSlug
    );
    this.absencePerson = false;
    this.hrmDataService.employeeList.searchEmpTxt = '';
    this.hrmSandboxService.getAllEmployee();
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
    this.hrmDataService.employeeList.list[idx].existing = false
  }
  /* Remove user from toUser list[end] */

  /* Select leave type [Start] */
  selectLeaveType(type) {
    this.hrmDataService.absent.type = [];
    this.hrmDataService.absent.type.push({ 'Slug': type.leaveTypeSlug, 'name': type.name });
    this.hrmDataService.absent.absentTypeSlug = type.leaveTypeSlug;
    this.absenceList = false;
  }
  /* Select leave type [end] */

  /* remove leave type [Start] */
  removeType() {
    this.hrmDataService.absent.type = [];
  }
  /* remove leave type [end] */

  /* validating absence [Start] */
  validateAbsense(): boolean {
    this.isValidated = true;
    if (!this.hrmDataService.toUsers.toUsers.length) this.isValidated = false;
    if (this.hrmDataService.absent.absentStartsOn === null) this.isValidated = false;
    if (this.hrmDataService.absent.absentEndsOn === null) this.isValidated = false;
    if (this.hrmDataService.absent.type.length === 0) this.isValidated = false;
    if (this.hrmDataService.absent.reason === '') this.isValidated = false;
    return this.isValidated;
  }
  /* validating absence [end] */

  /* create absence [Start] */
  createAbsence() {
    if (!this.validateAbsense()) return;
    this.hrmDataService.absent.absentStartsOn = this.utilityService.convertToUnix(this.hrmDataService.absent.absentStartsOn);
    this.hrmDataService.absent.absentEndsOn = this.utilityService.convertToUnix(this.hrmDataService.absent.absentEndsOn);
    this.hrmDataService.absent.action = 'create';
    this.hrmSandboxService.createAbsence();
  }
  /* create absence [end] */

  /* set half day[Start] */
  setStartsOnHalfDay(isHalfDay) {
    if (isHalfDay) {
      this.hrmDataService.absent.startsOnHalfDay = true;
    }
    else {
      this.hrmDataService.absent.startsOnHalfDay = false;
    }
  }
  setEndsOnHalfDay(isHalfDay) {
    if (isHalfDay) {
      this.hrmDataService.absent.endsOnHalfDay = true;
    }
    else {
      this.hrmDataService.absent.endsOnHalfDay = false;
    }
  }
  /* set half day[end] */

    /* clear date[Start] */
    clearAbsentStartsOn(){
      this.hrmDataService.absent.absentStartsOn = null;
    }
     
  clearAbsentEndsOn(){
    this.hrmDataService.absent.absentEndsOn = null;
  }
  /* clear date[end] */

  valudatedate(event){
    if(this.utilityService.convertToUnix(event) === this.utilityService.convertToUnix(this.hrmDataService.absent.absentStartsOn)){
     this.dateValidation = true;
    }
    else
    {
      this.dateValidation = false;
    }
  }
  updateAbsence(){
    if (!this.validateAbsense()) return;
    this.hrmDataService.absent.absentStartsOn = this.utilityService.convertToUnix(this.hrmDataService.absent.absentStartsOn);
    this.hrmDataService.absent.absentEndsOn = this.utilityService.convertToUnix(this.hrmDataService.absent.absentEndsOn);
    this.hrmSandboxService.createAbsence();
  }

  initOrChangeAbcentee(){
    this.hrmSandboxService.getAllEmployee();
  }
}
