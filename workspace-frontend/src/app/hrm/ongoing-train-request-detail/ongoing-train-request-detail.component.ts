import { HrmDataService } from './../../shared/services/hrm-data.service';
import {HrmSandboxService} from './../hrm.sandbox'
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-ongoing-train-request-detail',
  templateUrl: './ongoing-train-request-detail.component.html',
  styleUrls: ['./ongoing-train-request-detail.component.scss']
})
export class OngoingTrainRequestDetailComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService:HrmSandboxService
  ) { }

  ngOnInit() {
  }
  closeDetail() {
    this.hrmDataService.ongoingTrainingDetail.show = false;
  }

  deactivateRequest(){
    event.stopPropagation();
    this.hrmDataService.setTrainingRequestStatus = this.hrmDataService.selectedRequest.slug;
    this.hrmDataService.approveTrainingRequest.status = 'rejected';
    this.hrmDataService.setTrainingRequestStatus.hasFeedbackForm = false;
    this.hrmSandboxService.setTrainingRequestStatus();
   
  }
}
