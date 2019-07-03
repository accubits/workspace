import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-leave-type',
  templateUrl: './leave-type.component.html',
  styleUrls: ['./leave-type.component.scss']
})
export class LeaveTypeComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }

  ngOnInit() {
    this.hrmSandboxService.getLeaveTypeList();
  }

  /* show leave type details[start]*/
  showLeaveDetail(Slug) {
    this.hrmDataService.leaveType.leaveTypeSlug = Slug;
    let selData = this.hrmDataService.leaveType.leaveTypeList.filter(
      file => file.leaveTypeSlug === Slug)[0];
    this.hrmDataService.selectedData = selData;
    this.hrmDataService.leaveTypePop.show = true;
    this.hrmDataService.deletePopUp.show = false
  }
  /* show leave type details[end]*/

  /* sort leave type list by selected option[start]*/
  sortOperation(sortOption) {
    this.hrmDataService.leaveType.sortOption = sortOption,
      this.hrmDataService.leaveType.sortMethod === 'asc' ? this.hrmDataService.leaveType.sortMethod = 'des' : this.hrmDataService.leaveType.sortMethod = 'asc';
    this.hrmSandboxService.getLeaveTypeList();
  }
  /* sort leave type list by selected option[end]*/
}
