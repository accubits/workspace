import { Component, OnInit } from '@angular/core';
import { Routes, RouterModule, Router, ActivatedRoute,NavigationEnd ,Event} from '@angular/router';
import { TimeReportDataService } from '../../shared/services/time-report-data.service';
import { TimeReportSandbox } from '../time-report.sandbox';
import { Configs } from '../../config';

@Component({
  selector: 'app-time-report-nav',
  templateUrl: './time-report-nav.component.html',
  styleUrls: ['./time-report-nav.component.scss']
})
export class TimeReportNavComponent implements OnInit {
  monthName: string = '';
  showLegend: boolean = false;
  currentUrl = 'workTime'

  months = ["January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December"
  ];

  constructor(
    public timeReportDataService: TimeReportDataService,
    public timeReportSandbox: TimeReportSandbox,
    public router: Router,
  ) {
    router.events.subscribe((event: Event) => {
      if (event instanceof NavigationEnd ) {
        this.currentUrl = event.url;
        console.log(this.currentUrl);
      }
    });
   }
  public assetUrl = Configs.assetBaseUrl;
  ngOnInit() {
    let currDate = new Date(); // Getting current month and year
    this.timeReportDataService.getWorkTimeInput.monthYear.month = currDate.getMonth() + 1;
    this.monthName = this.months[currDate.getMonth()];
    this.timeReportDataService.getWorkTimeInput.monthYear.year = currDate.getFullYear();
    this.timeReportDataService.getWorkReportInput.year = currDate.getFullYear();

  }

   /*  navigating year and month */
  navigateMonthandYear(navigation): void {
    if (navigation === 'previous') {
      let monthIndex = this.months.indexOf(this.monthName)
      if (monthIndex === 0) { // If the month is januray going to previous year
        this.timeReportDataService.getWorkTimeInput.monthYear.month = this.months.length;
        this.monthName = this.months[this.months.length - 1];
        this.timeReportDataService.getWorkTimeInput.monthYear.year -= 1;
      } else {
        this.timeReportDataService.getWorkTimeInput.monthYear.month = monthIndex;
        this.monthName = this.months[monthIndex - 1];
      }
    } else {
      let monthIndex = this.months.indexOf(this.monthName)
      if (monthIndex === 11) { // If the month is December moving forward to next year
        this.timeReportDataService.getWorkTimeInput.monthYear.month = 1;
        this.monthName = this.months[0];
        this.timeReportDataService.getWorkTimeInput.monthYear.year += 1
      } else {
        this.timeReportDataService.getWorkTimeInput.monthYear.month = monthIndex + 2;
        this.monthName = this.months[monthIndex + 1];
      }
    }

    this.timeReportSandbox.getWorktimeReport();


  }

  /*  navigating yearfor report */
  navigateYear(navigation): void {
    if (navigation === 'previous') {
      this.timeReportDataService.getWorkReportInput.year -= 1;
    } else {
      this.timeReportDataService.getWorkReportInput.year += 1;
    }

    // getting work report with the change of month and year
     this.timeReportSandbox.getWorkReport();

  }
  showLegendPop(): void {
    this.showLegend = true;
  }
  closeLegendPop(): void {
    this.showLegend = false;
  }



}
