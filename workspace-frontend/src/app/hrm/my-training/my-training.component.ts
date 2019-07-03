import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import {HrmSandboxService} from './../hrm.sandbox'
import {UtilityService} from './../../shared/services/utility.service';
import * as _moment from 'moment';

@Component({
  selector: 'app-my-training',
  templateUrl: './my-training.component.html',
  styleUrls: ['./my-training.component.scss']
})
export class MyTrainingComponent implements OnInit {

  employeeList:any;
  trainTable: boolean = false;
  trainTableOngoing: boolean = false;
  trainTableCompleted: boolean = false;
  isValidated: boolean = false;
  isValidateOngoing: boolean = false;
  isValidatedCompleted: boolean = false;
  isValidateActive: boolean = false;
  moreOption: boolean = false;
  moreOptionFirst: boolean = false;
  trainTableActive: boolean = false;
  duration:null;

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService:HrmSandboxService,
    public utilityService : UtilityService) { }

 ngOnInit() {
      this.hrmDataService.getTrainingDetails.tab = 'myTrainings'
      this.hrmSandboxService.getRequestList();
      this.hrmSandboxService.getAllEmployee();
    }

  showTrainTable() {
    this.trainTable = !this.trainTable;
    this.isValidated = !this.isValidated;
  }

  showTrainDetail(i,training)  {
    this.hrmDataService.trainingDetail.show = true;
    this.hrmDataService.selectedRequest =this.hrmDataService.requestTable.list[i];
    var now = _moment(new Date()); 
    var end = _moment(this.utilityService.convertTolocale(training.endsOn)); 
    var duration = _moment.duration(now.diff(end));
    var days = duration.asDays();
  }

  showTrainTableOngoing() {
    this.trainTableOngoing = !this.trainTableOngoing;
    this.isValidateOngoing = !this.isValidateOngoing;
  }

  showTrainTableCompleted() {
    this.trainTableCompleted = !this.trainTableCompleted;
    this.isValidatedCompleted = !this.isValidatedCompleted;
  }

  showOngoingtrain(i) {
    this.hrmDataService.ongoingTraining.show = true;
    this.hrmDataService.selectedApprovedRequest=this.hrmDataService.requestTable.list[i];
  }

  showTrainTableActive() {
    this.trainTableActive = !this.trainTableActive;
    this.isValidateActive = !this.isValidateActive;
  }

  showMoreOption() {
    event.stopPropagation();
    this.moreOption = true;
  }

  hideMoreOption() {
    this.moreOption = false;
  }

  showMoreOptionFirst(event,i) {
    event.stopPropagation();
    this.hrmDataService.requestTable.list[i]['showMore'] = !this.hrmDataService.requestTable.list[i]['showMore'];
 }

  showMoreOptionRequest(event,i) {
    event.stopPropagation();
    this.hrmDataService.myTrainingTable.list[i]['showMores'] = !this.hrmDataService.myTrainingTable.list[i]['showMores'];
 }

  showMoreOptionActive(event,i) {
    event.stopPropagation();
    this.hrmDataService.requestTable.list[i]['showMoreActive'] = !this.hrmDataService.requestTable.list[i]['showMoreActive'];
  }

  hideMoreOptionFirst() {
    this.moreOptionFirst = false;
  }

  /*Update Training Request*/
  updateTrainingRequest(i,training):void{
    this.hrmDataService.trainingRequestForm.show = true;
    this.hrmDataService.createTrainingRequest.action = 'update';
    this.hrmDataService.createTrainingRequest.name = training.name;
    this.hrmDataService.createTrainingRequest.details = training.details;
     this.hrmDataService.createTrainingRequest.cost = training.cost;
     this.hrmDataService.createTrainingRequest.startsOn  = this.utilityService.convertTolocale(training.startsOn) ;
     this.hrmDataService.createTrainingRequest.endsOn = this.utilityService.convertTolocale(training.endsOn);
     this.hrmDataService.createTrainingRequest.slug = training.slug;

    this.hrmDataService.createTrainingRequest.approverEmployeeSlug =training.approverEmployeeSlug;
    this.hrmDataService.toUsers.toUsers.push({'name':training.approverUserName,'slug':training.approverEmployeeSlug})

  }

  deleteTrainingRequest(slug){
    this.hrmDataService.createTrainingRequest.slug = slug;
    this.hrmDataService.createTrainingRequest.action = 'delete';
    this.hrmDataService.getTrainingDetails.tab = 'myTrainings';
    this.hrmDataService.deleteTrainingRequestForm.show=true;

  }
  showActiveDetail(i) {
    this.hrmDataService.activeTrainingDetail.show = true;
    this.hrmDataService.selectedRequest =this.hrmDataService.requestTable.list[i];
  }
  showFinishedPop(i) {
    //alert("fsdfsdf");
    this.hrmDataService.finishedPop.show = true;
    this.hrmDataService.selectedRequest =this.hrmDataService.requestTable.list[i];
  }
}
