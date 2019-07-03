import { Component, OnInit } from '@angular/core';
import {TimeAndReportSandbox } from '../time-and-report.sandbox';

@Component({
  selector: 'app-worktime',
  templateUrl: './worktime.component.html',
  styleUrls: ['./worktime.component.scss']
})
export class WorktimeComponent implements OnInit {

  constructor(
    private timeAndReportSandbox:TimeAndReportSandbox
  ) { }

  ngOnInit() {
    this.timeAndReportSandbox.getWorktimeReport();
  }

}
