import { HrmDataService } from './../../shared/services/hrm-data.service';
import {HrmSandboxService} from './../hrm.sandbox'
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-training-request-form',
  templateUrl: './training-request-form.component.html',
  styleUrls: ['./training-request-form.component.scss']
})
export class TrainingRequestFormComponent implements OnInit {
  public todayDate: any = new Date();
  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }
  trainReqPerson: boolean = false;
  trainingList: boolean = false;

  ngOnInit() {
    this.hrmSandboxService.getAllEmployee();

  }

  selectEmployee(emp):void{
    this.hrmDataService.toUsers.toUsers = [];
    this.hrmDataService.toUsers.slug = [];
    this.hrmDataService.toUsers.toUsers.push({
      userSlug: emp.employeeSlug,
      name: emp.employeeName
    });
    this.hrmDataService.toUsers.slug.push(
      emp.employeeSlug
     );
    this.hrmDataService.createTrainingRequest.approverEmployeeSlug = emp.employeeSlug
    this.trainReqPerson=false;


  }

  closeStartDate() {
    this.hrmDataService.createTrainingRequest.startsOn = null;
  }
  closeEndDate() {
    this.hrmDataService.createTrainingRequest.endsOn = null;

  }


  hideTrainReqForm() {
    this.hrmDataService.trainingRequestForm.show = false;
    this.hrmDataService.selectedElement.isValidated=true;
    this.hrmDataService.resetTrainingRequest();
    this.hrmDataService.toUsers.toUsers = [];

  }

  /*Create Training Requests*/
  createTrainingRequest():void{
    this.hrmDataService.createTrainingRequest.approverEmployeeSlug = this.hrmDataService.reportingManagerDetails.reportingManagerSlug;
    if(!this.validateElement()){
      return;
    }
    this.hrmDataService.createTrainingRequest.action = 'create';
    this.hrmSandboxService.createTrainingRequests();
  }

  /*Validating all fields*/
validateElement(){
  (!this.hrmDataService.createTrainingRequest.approverEmployeeSlug||!this.hrmDataService.createTrainingRequest.details||!this.hrmDataService.createTrainingRequest.cost || !this.hrmDataService.createTrainingRequest.name || !this.hrmDataService.createTrainingRequest.startsOn||!this.hrmDataService.createTrainingRequest.endsOn || this.hrmDataService.createTrainingRequest.cost === '0')?
  this.hrmDataService.selectedElement.isValidated = false: this.hrmDataService.selectedElement.isValidated= true;
  return this.hrmDataService.selectedElement.isValidated;

}
  editTrainingRequest():void{
    if(!this.validateElement()){
      return;
    }
    this.hrmDataService.createTrainingRequest.action = 'update';
    this.hrmSandboxService.createTrainingRequests();
  }

  typeOfTraining(trainig):void{
    this.hrmDataService.createTrainingRequest.name = trainig;
    this.trainingList = false;

  }

   /* Remove user from toUser list [Start] */
 removeUsers(user): void {
  let existingUsers = this.hrmDataService.toUsers.toUsers.filter(
    part => part.userSlug === user.userSlug)[0];
  if (existingUsers) {
    let idx = this.hrmDataService.toUsers.toUsers.indexOf(existingUsers);
    if (idx !== -1) this.hrmDataService.toUsers.toUsers.splice(idx, 1);
  }

  let addUser = this.hrmDataService.employeeList.list.filter(
    part => part.slug === user.userSlug)[0];
  let idx = this.hrmDataService.employeeList.list.indexOf(addUser);
  this.hrmDataService.employeeList.list[idx].existing = false
}
/* Remove user from toUser list[end] */

closePop(){
  this.hrmDataService.trainingRequestForm.show = false;
  this.hrmDataService.selectedElement.isValidated=true;
  this.hrmDataService.resetTrainingRequest();
}
}
