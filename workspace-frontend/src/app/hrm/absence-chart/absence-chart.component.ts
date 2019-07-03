import { HrmSandboxService } from './../hrm.sandbox';
import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';

@Component({
  selector: 'app-absence-chart',
  templateUrl: './absence-chart.component.html',
  styleUrls: ['./absence-chart.component.scss']
})
export class AbsenceChartComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService,
  ) { }
  showHide: boolean = false;
  levDetail: boolean = false;

  ngOnInit() {
    let d = new Date();
    this.hrmDataService.absentChart.month = d.getMonth() + 1;
    this.hrmDataService.absentChart.year = d.getFullYear().toString();
    this.hrmDataService.absentChart.filter.departmentSlug = '';
    this.hrmDataService.absentChart.filter.leaveTypeSlugs = [];
    this.hrmSandboxService.getAbcentChartList();
  }

  showDetail() {
    this.hrmDataService.userList.show = !this.hrmDataService.userList.show ;
  }
  showAbsenceDetail(user,report)  {
    this.hrmDataService.absent.absentSlug = report.absenceSlug;
    this.hrmDataService.absentDetails.absentUser = user.userSlug;
    this.hrmDataService.absentDetails.absentUserName = user.userName;
    this.hrmDataService.absentDetails.absentUserImg = user.userImage;
    this.hrmDataService.absentDetails.absentStartsOn = report.leaveFrom;
    this.hrmDataService.absentDetails.absentEndsOn = report.leaveTo;
    this.hrmDataService.absentDetails.leaveType = report.leaveType;
    this.hrmDataService.absentDetails.reason = report.leaveReason;
    this.hrmDataService.absentDetails.leaveTypeColorCode = report.leaveTypeColorCode;
    this.hrmDataService.absenceDetail.show = true;
  }

  showLevDetail() {
    this.levDetail = true;
  }
  hideLevDetail() {
    this.levDetail = false;
  }
  abscenceDetailsShow(report,idx)
  {
    this.hrmDataService.absentDetails.absentStartsOn = report.leaveFrom;
    this.hrmDataService.absentDetails.absentEndsOn = report.leaveTo;
    this.hrmDataService.absentDetails.leaveType = report.leaveType;
    this.hrmDataService.absentDetails.reason = report.leaveReason;
    this.hrmDataService.absentDetails.leaveTypeColorCode = report.leaveTypeColorCode;
   this.hrmDataService.showLevDetail[idx] = true;
  }
  abscenceDetailsHide(index)
  {
   this.hrmDataService.showLevDetail[index] = false;
  }
}
