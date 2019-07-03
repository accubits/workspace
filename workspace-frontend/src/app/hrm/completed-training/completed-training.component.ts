import { HrmDataService } from './../../shared/services/hrm-data.service';
import {HrmSandboxService} from './../hrm.sandbox'
import {UtilityService} from '../../shared/services/utility.service'
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-completed-training',
  templateUrl: './completed-training.component.html',
  styleUrls: ['./completed-training.component.scss']
})
export class CompletedTrainingComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService : HrmSandboxService,
    public utilityService:UtilityService
  ) { }
  moreOption: boolean = false;
  ngOnInit() {
    this.hrmDataService.getTrainingDetails.tab = 'active'
    this.hrmSandboxService.getRequestList();
  }
  showCompletedDetail(i) {
    this.hrmDataService.activeTrainingPop.show = true;
    this.hrmDataService.selectedRequest =this.hrmDataService.requestTable.list[i];

  }
  showMoreOption(event,i) {
  event.stopPropagation();
  this.hrmDataService.requestTable.list[i]['showMore'] = !this.hrmDataService.requestTable.list[i]['showMore'];
}
hideMoreOption() {
  //this.moreOption['showMore'] = false;
  this.hrmDataService.moreOption['showMore'] = false
}

/*Update Training Request[Start]*/ 
updateTrainingRequest(i,training):void{
  this.hrmDataService.trainingRequestForm.show = true;
  this.hrmDataService.createTrainingRequest.action = 'update';
  // this.hrmDataService.selectedOption = 'request';
  this.hrmDataService.createTrainingRequest.name = training.name;
  this.hrmDataService.createTrainingRequest.details = training.details;
   this.hrmDataService.createTrainingRequest.cost = training.cost;
   this.hrmDataService.createTrainingRequest.startsOn  = this.utilityService.convertTolocale(training.startsOn) ;
   this.hrmDataService.createTrainingRequest.endsOn = this.utilityService.convertTolocale(training.endsOn);
   this.hrmDataService.createTrainingRequest.slug = training.slug;

   this.hrmDataService.createTrainingRequest.approverEmployeeSlug =training.approverEmployeeSlug;

    this.hrmDataService.toUsers.toUsers.push({'name':training.approverUserName,'slug':training.approverEmployeeSlug})
 
  }
  /*Update Training Request[End]*/ 

  /*Delete Training Request[Start]*/ 

  deleteTrainingRequest(slug){
    this.hrmDataService.deleteTrainingRequestForm.show=true;
    this.hrmDataService.createTrainingRequest.slug = slug;
    this.hrmDataService.createTrainingRequest.action = 'delete';
  }
  /*Delete Training Request[End]*/ 


}
