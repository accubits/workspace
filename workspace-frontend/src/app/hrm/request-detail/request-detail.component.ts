import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-request-detail',
  templateUrl: './request-detail.component.html',
  styleUrls: ['./request-detail.component.scss']
})
export class RequestDetailComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) {  }

  ngOnInit() {
  }

  closeEmpDetail() {
    this.hrmDataService.leavePop.show = false;
  }

  approveLeave(){
    this.hrmDataService.leaveCreate.action = 'aprove';
    this.hrmDataService.reqConformPopup.show = true;
    this.hrmDataService.deleteMessage.msg = 'Are you sure you want to approve selected leave request?'
    
  }

  conformApprove(){
    this.hrmSandboxService.approveLeave();
  }

  cancelLeave(){
    this.hrmDataService.leaveCreate.action = 'cancel';
    this.hrmDataService.reqConformPopup.show = true;
    this.hrmDataService.deleteMessage.msg = 'Are you sure you want to cancel selected leave request?'
  }
  
  conformCancel(){
    this.hrmSandboxService.createLeavReq();
  }

}
