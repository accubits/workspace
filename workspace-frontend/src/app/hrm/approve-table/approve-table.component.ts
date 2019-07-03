import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-approve-table',
  templateUrl: './approve-table.component.html',
  styleUrls: ['./approve-table.component.scss']
})
export class ApproveTableComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }

  ngOnInit() {
    this.hrmDataService.leaveList.tab = 'forApproval';
    this.hrmSandboxService.getLeaveList();
  }

  showEmpDetail(leave) {
   this.hrmDataService.leavePop.show = true;
   this.hrmDataService.requestDetails.leaveRequestSlug = leave.leaveRequestSlug;
   this.hrmDataService.leaveCreate.leaveSlug = leave.leaveRequestSlug;
   this.hrmDataService.requestDetails.dateFrom = leave.dateFrom;
   this.hrmDataService.requestDetails.startsOnHalfDay = leave.isLeaveStartHalfDay;
   this.hrmDataService.requestDetails.dateTo = leave.dateTo;
   this.hrmDataService.requestDetails.endsOnHalfDay = leave.isLeaveEndHalfDay;
   this.hrmDataService.requestDetails.userImage = leave.requestingUserImage;
   this.hrmDataService.requestDetails.requestToImage = leave.departmentHeadUserImage;
   this.hrmDataService.requestDetails.reason = leave.reason;
   this.hrmDataService.requestDetails.colorCode = leave.colorCode;
   this.hrmDataService.requestDetails.userName = leave.requestingUserName;
   this.hrmDataService.requestDetails.requestToName = leave.departmentHeadUserName;
   this.hrmDataService.requestDetails.leaveType = leave.leaveType;
   this.hrmDataService.requestDetails.isApprove = leave.isApproveBtn;
  }
  
  approveLeave(leave){
    this.hrmDataService.leaveCreate.action = 'aprove';
    this.hrmDataService.requestDetails.leaveRequestSlug = leave.leaveRequestSlug;
    this.hrmDataService.deletePopUp.show = true;
    this.hrmDataService.deleteMessage.msg = 'Are you sure you want to approve selected leave request?'
  }
  conformApprove(){
    this.hrmSandboxService.approveLeave();
  }

  cancelLeaveReq(leave){
    this.hrmDataService.leaveCreate.action = 'cancel';
    this.hrmDataService.leaveCreate.leaveSlug = leave.leaveRequestSlug;
    this.hrmDataService.deletePopUp.show = true;
    this.hrmDataService.deleteMessage.msg = 'Are you sure you want to cancel selected leave request?'
  }
  conformCancel(){
    this.hrmDataService.resetPopUp();
    this.hrmSandboxService.createLeavReq();
  }

   /* sort employees by selected option[start]*/
   sortOperation(sortOption) {
    this.hrmDataService.leaveCreate.sortOption = sortOption,
      this.hrmDataService.leaveCreate.sortMethod === 'asc' ? this.hrmDataService.leaveCreate.sortMethod = 'desc' : this.hrmDataService.leaveCreate.sortMethod = 'asc';
    this.hrmSandboxService.getLeaveList();
  }
  /* sort employees by selected option[end]*/
}