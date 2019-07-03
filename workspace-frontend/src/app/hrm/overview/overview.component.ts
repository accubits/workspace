import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';
import {HrmSandboxService} from './../hrm.sandbox';

@Component({
  selector: 'app-overview',
  templateUrl: './overview.component.html',
  styleUrls: ['./overview.component.scss']
})
export class OverviewComponent implements OnInit {


  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }
  moreOption: boolean = false;
  ngOnInit() {
    this.hrmDataService.getTrainingDetails.tab = 'overview';
    this.hrmSandboxService.getRequestList();
  }
  showMoreOption() {
    event.stopPropagation();
    this.moreOption = true;
  }
  hideMoreOption() {
    this.moreOption = false;
  }
  showOverviewDetail(overviewTraining) {
    this.hrmDataService.completedTrainingDetail.show = true;
    this.hrmDataService.selectOverviewDetails = overviewTraining;
  }
}
