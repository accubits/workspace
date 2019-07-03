import { Router } from '@angular/router';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-performance-head',
  templateUrl: './performance-head.component.html',
  styleUrls: ['./performance-head.component.scss']
})
export class PerformanceHeadComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public router: Router,
  ) { }

  ngOnInit() {
  }
  showNewModule() {
    this.hrmDataService.newModule.show = true;
  }
  addAppraisalPop(){
    this.hrmDataService.addAppraisal.show = true;
  }
}
