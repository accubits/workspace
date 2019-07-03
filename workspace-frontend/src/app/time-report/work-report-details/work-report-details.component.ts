import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { TimeReportDataService } from '../../shared/services/time-report-data.service' ;
import { TimeReportSandbox } from '../time-report.sandbox'


@Component({
  selector: 'app-work-report-details',
  templateUrl: './work-report-details.component.html',
  styleUrls: ['./work-report-details.component.scss']
})
export class WorkReportDetailsComponent implements OnInit {

  constructor(
    public timeReportDataService: TimeReportDataService,
    public timeReportSandbox : TimeReportSandbox
  ) { }

  ngOnInit() {
    console.log('seleted repoty',this.timeReportDataService.selectedWorkReportDetails)
  }
  closeWorkReportDetails():void{
    this.timeReportDataService.workReportDetails.show = false;
  }
  confirmReport(){
    // this.timeReportDataService.selectedWorkReportDetails.reportSlug

    this.timeReportSandbox.confirmReport();
  }

}
