import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';
import { UtilityService } from '../../shared/services/utility.service';

@Component({
  selector: 'app-leave-request',
  templateUrl: './leave-request.component.html',
  styleUrls: ['./leave-request.component.scss']
})
export class LeaveRequestComponent implements OnInit {
 
  isValidated = true;
  isDateValidated = true;
  requestTo: boolean = false;
  reasonList: boolean = false;
  dateValidation: boolean = false; 
  public todayDate: any = new Date();
  
  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService,
    private utilityService: UtilityService
  ) {  }
  
  ngOnInit() {
    this.hrmSandboxService.getLeaveTypeList();
  }
  closeLeavReq() {
    this.hrmDataService.resetLeave();
    this.hrmDataService.requestPop.show = false;
  }
  requestToShow()  {
    this.requestTo = true;
  }
  requestToHide() {
    this.requestTo = false;
  }
  reasonShow()  {
    this.reasonList = true;
  }
  reasonHide() {
    this.reasonList = false;
  }

  selectLeaveType(type){
    this.hrmDataService.leaveCreate.type = [];
    this.hrmDataService.leaveCreate.type.push({'Slug':type.leaveTypeSlug, 'name': type.name});
    this.hrmDataService.leaveCreate.leaveTypeSlug = type.leaveTypeSlug;
    this.reasonList = false;
  }
   /* Validating creating new message[Start] */
  
   removeType(){
    this.hrmDataService.leaveCreate.type = [];
   }

 

 validateLeavReq(): boolean {
  this.isValidated = true;
    if (this.hrmDataService.leaveCreate.leaveStartsOn === '') this.isValidated = false;
    if (this.hrmDataService.leaveCreate.leaveEndsOn === '') this.isValidated = false;
    if (this.hrmDataService.leaveCreate.type.length === 0) this.isValidated = false;
    if (this.hrmDataService.leaveCreate.reason === '') this.isValidated = false;
    return this.isValidated;
}
/* Validating creating new message[end] */

createLeavReq() {
  if (!this.validateLeavReq()) return;
  this.hrmDataService.leaveCreate.leaveSlug = null;
  this.hrmDataService.leaveCreate.action = 'create';
  this.hrmDataService.toUsers.slug = this.hrmDataService.reportingManagerDetails.reportingManagerSlug;
  this.hrmDataService.leaveCreate.leaveStartsOn = this.utilityService.convertToUnix(this.hrmDataService.leaveCreate.leaveStartsOn);
  this.hrmDataService.leaveCreate.leaveEndsOn = this.utilityService.convertToUnix(this.hrmDataService.leaveCreate.leaveEndsOn);
  this.hrmSandboxService.createLeavReq();
}

clearStartDate(){
  this.hrmDataService.leaveCreate.leaveStartsOn = '';
}

clearEndDate(){
  this.hrmDataService.leaveCreate.leaveEndsOn = '';
  this.hrmDataService.leaveCreate.endsOnHalfDay = false;
}

valudatedate(event){
  if(this.utilityService.convertToUnix(event) === this.utilityService.convertToUnix(this.hrmDataService.leaveCreate.leaveStartsOn)){
   this.dateValidation = true;
  }
  else
  {
    this.dateValidation = false;
  }
}

closePop(){
  this.hrmDataService.requestPop.show = false;
}
}
