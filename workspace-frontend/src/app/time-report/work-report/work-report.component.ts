import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { TimeReportDataService } from '../../shared/services/time-report-data.service';
import { TimeReportSandbox } from '../time-report.sandbox';

@Component({
  selector: 'app-work-report',
  templateUrl: './work-report.component.html',
  styleUrls: ['./work-report.component.scss']
})
export class WorkReportComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(
    public timeReportDataService: TimeReportDataService,
    public timeReportSandbox: TimeReportSandbox,
  ) { }

  ngOnInit() {
     this.timeReportSandbox.getWorkReport(); // Getting work report
  }
  
  getDetailedWorkReport(report): void{
    if(!report.reportSlug){
      return;
    }
    this.timeReportDataService.detailedWorkReportInput.reportSlug = report.reportSlug;
    this.timeReportSandbox.getDetailedWorkReport();
    this.timeReportDataService.workReportDetails.show = true;
  }

}
