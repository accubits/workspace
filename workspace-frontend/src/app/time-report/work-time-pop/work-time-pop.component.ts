import { Component, OnInit } from '@angular/core';
import { TimeReportDataService } from '../../shared/services/time-report-data.service';

@Component({
  selector: 'app-work-time-pop',
  templateUrl: './work-time-pop.component.html',
  styleUrls: ['./work-time-pop.component.scss']
})
export class WorkTimePopComponent implements OnInit {

  constructor(
    public timeReportDataService: TimeReportDataService,
  ) { }

  ngOnInit() {
  }

  hideWorkDetail(){
    this.timeReportDataService.workTimeDetailPop.show = false;
  }
}
