import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';
import { HrmSandboxService } from '../hrm.sandbox';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-leave-type-detail',
  templateUrl: './leave-type-detail.component.html',
  styleUrls: ['./leave-type-detail.component.scss']
})
export class LeaveTypeDetailComponent implements OnInit {
  imageUrl = '';
  checkRole:string;
  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService,
    public cookieService: CookieService
  ) { }

  ngOnInit() {
    if(this.hrmDataService.selectedData.allEmployees !== true){
      this.imageUrl = this.hrmDataService.selectedData.users[0].imageUrl;
    }
    this.checkRole= this.cookieService.get('role');
   }
  closeLevDetail() {
    this.hrmDataService.leaveTypePop.show = false;
  }

  delete(){
    this.hrmDataService.deletePopUp.show = true;
    this.hrmDataService.leaveType.action = 'delete';
    this.hrmDataService.deleteMessage.msg = 'Are you sure you want to delete selected leave type?';
   }

   edit(){
    this.hrmDataService.leaveTypePop.show = false;
    this.hrmDataService.leaveType.action = 'update';
    this.hrmDataService.leaveType.name = this.hrmDataService.selectedData.name;
    if (this.hrmDataService.selectedData.type === 'Paid') {
      this.hrmDataService.leaveType.type = 'paid';
    }
    if (this.hrmDataService.selectedData.type === 'Un Paid') {
      this.hrmDataService.leaveType.type = 'unpaid';
    }
    if (this.hrmDataService.selectedData.type === 'On Duty') {
      this.hrmDataService.leaveType.type = 'onduty';
    }
    this.hrmDataService.leaveType.colorCode = this.hrmDataService.selectedData.colorCode;
    this.hrmDataService.leaveType.description = this.hrmDataService.selectedData.description;
    this.hrmDataService.toUsers.toUsers = this.hrmDataService.selectedData.users;
    this.hrmDataService.toUsers.slug = [];
    for(let i = 0; i< this.hrmDataService.selectedData.users.length; i++){
      this.hrmDataService.toUsers.slug.push(this.hrmDataService.selectedData.users[i].userSlug);
    }
    this.hrmDataService.toUsers.toAllEmployee = this.hrmDataService.selectedData.allEmployees;
    this.hrmDataService.leaveType.leaveCount = this.hrmDataService.selectedData.maximumLeave;
    this.hrmDataService.leaveType.isApplicableFor = this.hrmDataService.selectedData.isApplicableFor;
    this.hrmDataService.newLeavePop.show = true;
   }

   conformDelete(){
    this.hrmSandboxService.createLeaveType();
   }

}

