import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { Routes, RouterModule, Router, ActivatedRoute, NavigationEnd, Event } from '@angular/router';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-emp-detail',
  templateUrl: './emp-detail.component.html',
  styleUrls: ['./emp-detail.component.scss']
})
export class EmpDetailComponent implements OnInit {
  inform = 'information';
  checkRole: string = '';
  loggedUser = '';
  yearPick = false;
  year = [];
  constructor(
    public router: Router,
    public hrmDataService: HrmDataService,
    public cookieService: CookieService,
    public hrmSandboxService: HrmSandboxService
  ) { }
  ngOnInit() {
    let currDate = new Date(); // Getting current month and year
    this.hrmDataService.leaveList.levelist.year = currDate.getFullYear();
    this.checkRole = this.cookieService.get('role');
    this.loggedUser = this.cookieService.get('userSlug');
    this.hrmSandboxService.getEmployeeleave();
  }
  /* hide employee detaila popup[Start] */
  hideEmpDetail() {
    this.hrmDataService.empDetail.show = false;
  }
  showEditEmp() {
    this.hrmDataService.editEmpDetail.show = true;
  }
  /* hide employee details popup[end] */
  showYearPick() {
    this.year = [];
    var currDate = new Date();
    var joiningDate = new Date(this.hrmDataService.getEmployeeDetails.joiningDate * 1000);
    var curYear = currDate.getFullYear();
    var joYear = joiningDate.getFullYear();
    if (joYear < curYear) {
      for (let i = joYear; i <= curYear; i++) {
        this.year.push(i);
      }
    }
    else {
      this.year.push(curYear);
    }
    this.yearPick = !this.yearPick;
  }
  hideYearPick() {
    this.yearPick = false;
  }

  selectYear(year){
    this.hrmDataService.leaveList.levelist.year = year;
    this.hrmSandboxService.getEmployeeleave();
  }
}
