import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-my-performance',
  templateUrl: './my-performance.component.html',
  styleUrls: ['./my-performance.component.scss']
})
export class MyPerformanceComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
  ) { }

  ngOnInit() {
  }
  showMoreOption() {
    event.stopPropagation();
    this.hrmDataService.moreOption.show = true;
  }
  hideMoreOption() {
    this.hrmDataService.moreOption.show = false;
  }
  showDetailPop() {
     this.hrmDataService.myPerformanceDetail.show = true;
  }
}
