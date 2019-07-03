import { Component, OnInit } from '@angular/core';
import { TimeReportDataService } from '../../shared/services/time-report-data.service'

@Component({
  selector: 'app-work-report-filter',
  templateUrl: './work-report-filter.component.html',
  styleUrls: ['./work-report-filter.component.scss']
})
export class WorkReportFilterComponent implements OnInit {

  droplistSection:boolean = false;

  constructor(
    public timeReportDataService: TimeReportDataService,
  ) { }

  ngOnInit() {
  }
  closeFilter(): void{
    this.timeReportDataService.filterPopup.show = false;
  }
  closelistSection()
  {
    this.droplistSection = false;
  }
}
