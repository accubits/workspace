import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-ongoing-training-details',
  templateUrl: './ongoing-training-details.component.html',
  styleUrls: ['./ongoing-training-details.component.scss']
})
export class OngoingTrainingDetailsComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }

  ngOnInit() {
  }
  closeOngoingTraining(){
    this.hrmDataService.ongoingTraining.show = false;
  }

  startingTraining() {
    this.hrmDataService.setTrainingStatus.slug = this.hrmDataService.selectedApprovedRequest.slug;
    this.hrmDataService.setTrainingStatus.status = 'start';
    this.hrmSandboxService.setTrainingstatus();
    this.hrmDataService.ongoingTraining.show = false;
  }

  cancelTraining(){
    this.hrmDataService.deleteConfirmPopup.show = true;
  }
  
  cancelConform(){
this.hrmDataService.setTrainingStatus.slug = this.hrmDataService.selectedApprovedRequest.slug;
  this.hrmDataService.setTrainingStatus.status = 'cancel';
  this.hrmSandboxService.setTrainingstatus();
  this.hrmDataService.ongoingTraining.show = false;
  }
  
}
