import { Component, OnInit } from '@angular/core';
import { Routes, RouterModule, Router, ActivatedRoute } from '@angular/router';
import { Configs } from '../config';
import { TimeReportDataService } from '../shared/services/time-report-data.service' 

@Component({
  selector: 'app-time-report',
  templateUrl: './time-report.component.html',
  styleUrls: ['./time-report.component.scss']
})
export class TimeReportComponent implements OnInit {

  workReportFilter:boolean = false;
  constructor(
    public timeReportDataService: TimeReportDataService,
    public router: Router,
  ) { }
  public assetUrl = Configs.assetBaseUrl;
  ngOnInit() {
  }

  showFilter(): void{
    this.timeReportDataService.filterPopup.show = true;
  }
  showWorkTimeFilter():void{
    this.timeReportDataService.workTimeFilterPopup.show = true;
  }

  showCreateReport():void{
    this.timeReportDataService.createReport.show = true;
  }

  closeFilter(): void{
    this.timeReportDataService.filterPopup.show = false;
  }
  closeWorkTimeFilter(): void{
    this.timeReportDataService.workTimeFilterPopup.show = false;
  }
  closeWorkTimeDetails(): void{
    this.timeReportDataService.workTimeDetails.show = false;
  }
  closeWorkReportDetails():void{
    this.timeReportDataService.workReportDetails.show = false;
  }
}
