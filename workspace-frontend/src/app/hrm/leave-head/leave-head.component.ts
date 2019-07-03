import { Component, OnInit } from '@angular/core';
import { Routes, RouterModule, Router, ActivatedRoute, NavigationEnd , Event} from '@angular/router';
import { HrmDataService} from '../../shared/services/hrm-data.service';
import { CookieService } from 'ngx-cookie-service';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-leave-head',
  templateUrl: './leave-head.component.html',
  styleUrls: ['./leave-head.component.scss']
})
export class LeaveHeadComponent implements OnInit {
  checkRole:string;
  constructor(
    public hrmDataService: HrmDataService,
    public router: Router,
    public cookieService: CookieService,
    public hrmSandboxService: HrmSandboxService
  ) { }

  ngOnInit() {
    this.checkRole= this.cookieService.get('role');
    this.hrmDataService.resetreportingManagerDetails();
    this.hrmSandboxService.getProfile();

  }
  showLeaveRequest() {
    this.hrmDataService.requestPop.show = true;
  }
  newLeaveType() {
    this.hrmDataService.leaveType.action = 'create';
    this.hrmDataService.resetLeaveType();
    this.hrmDataService.newLeavePop.show = true;
  }
  showAddHoliday() {
    this.hrmDataService.addHoliday.show = true;
  }
  showAbsenceFilter() {
    this.hrmDataService.absenceFilter.show = true;
  }
  showNewAbsence() {
    this.hrmDataService.newAbsence.show = true;
  }
}
