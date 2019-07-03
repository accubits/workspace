import { HrmSandboxService } from './../hrm.sandbox';
import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import {UtilityService} from './../../shared/services/utility.service'

@Component({
  selector: 'app-training-request-table',
  templateUrl: './training-request-table.component.html',
  styleUrls: ['./training-request-table.component.scss']
})
export class TrainingRequestTableComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService,
    public utilityService : UtilityService

  ) { }
  moreOption: boolean = false;
  ngOnInit() {
    this.hrmDataService.getTrainingDetails.tab = 'request'
    this.hrmSandboxService.getRequestList();
  }
  showTrainingRequest(i) {
    this.hrmDataService.trainingRequest.show = true;
    this.hrmDataService.selectedRequest =this.hrmDataService.requestTable.list[i];
  }
}
