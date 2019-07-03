import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from './../hrm.sandbox'
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-training-detail',
  templateUrl: './training-detail.component.html',
  styleUrls: ['./training-detail.component.scss']
})
export class TrainingDetailComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }

  ngOnInit() {
  }
  closetrainDetail() {
    this.hrmDataService.trainingDetail.show = false;
  }


  cancelTrainingRequests() {
    this.hrmDataService.deletePopUp.show = true;
   
  }
  confirmCancel(){
    this.hrmDataService.setTrainingStatus.slug = this.hrmDataService.selectedRequest.slug;
    this.hrmDataService.setTrainingStatus.status = 'cancel';
    this.hrmSandboxService.setTrainingstatus();
  }

  closePopUp(){
    this.hrmDataService.deletePopUp.show = false;
  }
}
