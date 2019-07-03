import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { TimeReportDataService } from '../../shared/services/time-report-data.service'
import { TimeReportSandbox } from '../time-report.sandbox'

@Component({
  selector: 'app-work-time-details',
  templateUrl: './work-time-details.component.html',
  styleUrls: ['./work-time-details.component.scss']
})
export class WorkTimeDetailsComponent implements OnInit {

  constructor(
    public timeReportDataService: TimeReportDataService,
    public timeReportSandbox: TimeReportSandbox
  ) { }

  ngOnInit() {
  }

  closeDetails(): void{
    this.timeReportDataService.workTimeDetails.show = false;
  }

  /* Confirm Daily report */
  confirmDailyReport():void{
    this.timeReportSandbox.confirmDailyReport();
  }

}
