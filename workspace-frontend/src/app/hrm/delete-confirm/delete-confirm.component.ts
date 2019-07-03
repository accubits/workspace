import { Component, OnInit } from '@angular/core';
import {HrmDataService} from './../../shared/services/hrm-data.service';
import {HrmSandboxService} from './../hrm.sandbox'


@Component({
  selector: 'app-delete-confirm',
  templateUrl: './delete-confirm.component.html',
  styleUrls: ['./delete-confirm.component.scss']
})
export class DeleteConfirmComponent implements OnInit {

  constructor(
    public hrmDataService:HrmDataService,
    public hrmSandboxService:HrmSandboxService
  ) { }

  ngOnInit() {
  }

  closePopup(){
    this.hrmDataService.deleteTrainingRequestForm.show=false;
  }

  /*Delete Training Request*/

  confirmDelete(){
    // if(this.hrmDataService.selectedOption = 'request')
    // {
    //   this.hrmDataService.getTrainingDetails.tab = 'request';
    // }
    // if(this.hrmDataService.selectedOption = 'mytraining'){
    //   this.hrmDataService.getTrainingDetails.tab = 'myTrainings';
    // }
    this.hrmSandboxService.createTrainingRequests();
  }

}
