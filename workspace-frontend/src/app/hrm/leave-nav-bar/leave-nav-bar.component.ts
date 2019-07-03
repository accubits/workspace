import { Component, OnInit } from '@angular/core';
import { Routes, RouterModule, Router, ActivatedRoute, NavigationEnd , Event} from '@angular/router';
import { CookieService } from 'ngx-cookie-service';
import { HrmDataService} from '../../shared/services/hrm-data.service';
import { HrmSandboxService } from './../hrm.sandbox';

@Component({
  selector: 'app-leave-nav-bar',
  templateUrl: './leave-nav-bar.component.html',
  styleUrls: ['./leave-nav-bar.component.scss']
})
export class LeaveNavBarComponent implements OnInit {
  isDepartmentHead: String = '';
  checkRole: string= '';
  monthName: string = '';
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
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService,
    public router: Router,
    public cookieService: CookieService
  ) { }

  ngOnInit() {
    this.checkRole  = this.cookieService.get('role');
    this.isDepartmentHead  = this.cookieService.get('isDepartmentHead');
    let currDate = new Date(); // Getting current month and year
    this.hrmDataService.absentChart.month = currDate.getMonth() + 1;
    this.monthName = this.months[currDate.getMonth()];
    this.hrmDataService.absentChart.monthYear = currDate.getFullYear();
    this.hrmDataService.absentChart.year = currDate.getFullYear();
}
  /*  navigating year and month */
  navigateMonthandYear(navigation): void {
    if (navigation === 'previous') {
      let monthIndex = this.months.indexOf(this.monthName)
      if (monthIndex === 0) { // If the month is januray going to previous year
        this.hrmDataService.absentChart.month = this.months.length;
        this.monthName = this.months[this.months.length - 1];
        this.hrmDataService.absentChart.year -= 1;
        this.hrmSandboxService.getAbcentChartList();
       } else {
        this.hrmDataService.absentChart.month = monthIndex;
        this.monthName = this.months[monthIndex - 1];
        this.hrmSandboxService.getAbcentChartList();
      }
    } else {
      let monthIndex = this.months.indexOf(this.monthName)
      if (monthIndex === 11) { // If the month is December moving forward to next year
        this.hrmDataService.absentChart.month = 1;
        this.monthName = this.months[0];
        this.hrmDataService.absentChart.year += 1;
        this.hrmSandboxService.getAbcentChartList();
      } else {
        this.hrmDataService.absentChart.month = monthIndex + 2;
        this.monthName = this.months[monthIndex + 1];
        this.hrmSandboxService.getAbcentChartList();
      }
    }

  }

  goToToday(){
    let currDate = new Date(); // Getting current month and year
    this.hrmDataService.absentChart.month = currDate.getMonth() + 1;
    this.hrmDataService.absentChart.year = currDate.getFullYear();
    this.monthName = this.months[currDate.getMonth()];
    this.hrmSandboxService.getAbcentChartList();
  }
}
