import { Component, OnInit } from '@angular/core';
import {HrmDataService} from './../../shared/services/hrm-data.service';
import { CookieService } from 'ngx-cookie-service';

import { Routes, RouterModule, Router, ActivatedRoute, NavigationEnd , Event} from '@angular/router';

@Component({
  selector: 'app-training-nav',
  templateUrl: './training-nav.component.html',
  styleUrls: ['./training-nav.component.scss']
})
export class TrainingNavComponent implements OnInit {
  checkRole: string= '';


  constructor(
    public router: Router,
    public hrmDataService:HrmDataService,
    public cookieService: CookieService

  ) { }

  ngOnInit() {
    this.checkRole = this.cookieService.get('role');

  }

  selectTab(tabStatus){
    this.hrmDataService.getTrainingDetails.tab = tabStatus;
  }
}
