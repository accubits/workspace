import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-active-detail-pop',
  templateUrl: './active-detail-pop.component.html',
  styleUrls: ['./active-detail-pop.component.scss']
})
export class ActiveDetailPopComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }

  ngOnInit() {
  }
  completeTraining() {
    this.hrmDataService.setTrainingStatus.slug =  this.hrmDataService.selectedRequest.slug;
    this.hrmDataService.setTrainingStatus.status = 'completed';
    this.hrmSandboxService.setTrainingstatus();
    this.hrmDataService.ongoingTraining.show = false;
    this.hrmDataService.activeTrainingDetail.show = false;
  }
  closeActiveTraining() {
this.hrmDataService.activeTrainingDetail.show = false;
  }
}
