import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { TimeReportDataService } from '../../shared/services/time-report-data.service';
import { TimeReportSandbox } from '../time-report.sandbox';

@Component({
  selector: 'app-work-time',
  templateUrl: './work-time.component.html',
  styleUrls: ['./work-time.component.scss']
})
export class WorkTimeComponent implements OnInit {
  
  public assetUrl = Configs.assetBaseUrl;
  constructor(
    public timeReportDataService: TimeReportDataService,
    public timeReportSandbox: TimeReportSandbox,
  ) { }

  ngOnInit() {
    // console.log('time and report', this.timeReportDataService. workTimeReport.workTime.users.userImage)
    this.timeReportSandbox.getWorktimeReport(); // Getting work time report
  }

  /* showing selected work time details */
  workTimeDetails(deptSlug,reportSlug): void{
    if(!reportSlug)return;
    this.timeReportDataService.getSelectedWorkTimeInput.departmentSlug = deptSlug;
    this.timeReportDataService.getSelectedWorkTimeInput.reportSlug = reportSlug;
    this.timeReportSandbox.getSelectedReportDetails(); 
    this.timeReportDataService.workTimeDetails.show = true;
  }

  workTimePop(report,user){
    console.log('usert',user)
    this.timeReportDataService.workTimeDetailPop.show = true;
    this.timeReportDataService.absentDetails.leaveTypeName = report.absent.leaveTypeName;
    this.timeReportDataService.absentDetails.leaveTypeColorCode = report.absent.colorCode;
    this.timeReportDataService.absentDetails.absentStartsOn = report.absent.absentStartDate;

    this.timeReportDataService.absentDetails.absentUserName = user.name;
    this.timeReportDataService.absentDetails.absentUserImg = user.userImage;
  }
 
}
