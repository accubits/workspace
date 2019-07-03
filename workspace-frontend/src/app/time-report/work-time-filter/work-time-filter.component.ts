import { Component, OnInit } from '@angular/core';
import { TimeReportDataService } from '../../shared/services/time-report-data.service'
import { TimeReportSandbox } from '../time-report.sandbox'


@Component({
  selector: 'app-work-time-filter',
  templateUrl: './work-time-filter.component.html',
  styleUrls: ['./work-time-filter.component.scss']
})
export class WorkTimeFilterComponent implements OnInit {
  droplistSection: boolean = false;

  workTimeFilter = {
    confirmed: false,
    unConfirmed: false,
    departmentSlug: null
  }

  selDeptName: '';


  constructor(
    public timeReportDataService: TimeReportDataService,
    public timeReportSandbox: TimeReportSandbox,
  ) { }

  ngOnInit() {
    this.timeReportSandbox.getAllDepts();
  }
  closeWorkTimeFilter(): void {
    this.timeReportDataService.workTimeFilterPopup.show = false;
  }


  /* Selecting Dept */
  selctDept(selDept): void {
    this.workTimeFilter.departmentSlug = selDept.departmentSlug;
    this.selDeptName = selDept.departmentName;
  }

  /* Apply Filter */
  applyFilter(): void {
    this.timeReportDataService.getWorkTimeInput.filterBy = this.workTimeFilter;
    this.timeReportSandbox.getWorktimeReport();
  }
  /* Reset Filter */
  resetFilter(): void {
    this.workTimeFilter = {
      confirmed: false,
      unConfirmed: false,
      departmentSlug: null
    }
    this.timeReportDataService.getWorkTimeInput.filterBy = {};
    this.timeReportSandbox.getWorktimeReport();
  }
  closelistSection() {
this.droplistSection = false;
  }
}
