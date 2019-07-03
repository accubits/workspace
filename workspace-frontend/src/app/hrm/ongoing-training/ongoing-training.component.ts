import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import {UtilityService} from './../../shared/services/utility.service'
import {HrmSandboxService} from './../hrm.sandbox'

@Component({
  selector: 'app-ongoing-training',
  templateUrl: './ongoing-training.component.html',
  styleUrls: ['./ongoing-training.component.scss']
})
export class OngoingTrainingComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService:HrmSandboxService,
    public utilityService:UtilityService
  ) { }
    moreOption: boolean = false;
  ngOnInit() {
    this.hrmDataService.getTrainingDetails.tab = 'approved'
    this.hrmSandboxService.getRequestList();
  }
  showDetail(i) {
    this.hrmDataService.ongoingTrainingDetail.show = true;
    this.hrmDataService.selectedRequest =this.hrmDataService.requestTable.list[i];
  }
  showMoreOption(event,i) {
    event.stopPropagation();
    this.hrmDataService.requestTable.list[i]['showMore'] = !this.hrmDataService.requestTable.list[i]['showMore'];

    this.moreOption = true;
  }
  hideMoreOption() {
    this.moreOption = false;
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
