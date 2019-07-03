import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from './../hrm.sandbox'
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-training-request',
  templateUrl: './training-request.component.html',
  styleUrls: ['./training-request.component.scss']
})
export class TrainingRequestComponent implements OnInit {

  selectDrop: boolean = false;
  selectForm = false;
  courseForm = false;
  approvePop = false;
  postTrainingForm = '';
  postCourseForm = '';
  showWariningPopUp = false;

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }

  ngOnInit() {
    this.hrmDataService.getAllForms.tab = 'active';
    this.hrmDataService.getAllForms.type = 'postCourseFeedbackForm';
    this.hrmSandboxService.getCourseForm();
    this.hrmDataService.getAllForms.type = 'postTrainingFeedbackForm';
    this.hrmSandboxService.getFeedbackForm();
  }
  
  hideTrainingRequest() {
    this.hrmDataService.trainingRequest.show = false;
  }

  /*Delete Training Requests[Start]*/
  deletetrainingDetail() {
    this.hrmDataService.createTrainingRequest.action = 'delete'
    this.hrmDataService.createTrainingRequest.slug = this.hrmDataService.selectedRequest.slug;
    this.hrmSandboxService.createTrainingRequests();
  }
  /*Delete Training Requests[End]*/

  /*Approve Training Requests[Start]*/
  approveRequest() {
    this.hrmDataService.setTrainingRequestStatus.slug = this.hrmDataService.selectedRequest.slug;
    this.hrmDataService.setTrainingRequestStatus.status = 'approved';
    this.hrmDataService.setTrainingRequestStatus.hasFeedbackForm = true;
    this.hrmSandboxService.setTrainingRequestStatus();
  }
  /*Approve Training Requests[End]*/

  showSelectForm() {
    this.selectForm = !this.selectForm;
  }
  hideSelectForm() {
    this.selectForm = false;
  }
  showCourseForm() {
    
    this.courseForm = !this.courseForm;
  }
  hideCourseForm() {
    this.courseForm = false;
  }
  showApprovePop() {
    this.approvePop = true;
  }
  hideApprovePop() {
    this.approvePop = false;
  }

  selectTraining(form) {
    this.postTrainingForm = form.formTitle;
    this.hrmDataService.setTrainingRequestStatus.forms.postTrainingFormSlug = form.formSlug;
    this.selectForm = false;
  }

  selectCourse(form) {
    this.postCourseForm = form.formTitle;
    this.hrmDataService.setTrainingRequestStatus.forms.postCourseFormSlug = form.formSlug;
    this.courseForm = false;
   }

  rejectTraining() {
    this.hrmDataService.deleteConfirmPopup.show = true;
  }

  deleteConform() {
    this.hrmDataService.setTrainingRequestStatus.slug = this.hrmDataService.selectedRequest.slug;
    this.hrmDataService.setTrainingRequestStatus.hasFeedbackForm = false;
    this.hrmDataService.setTrainingRequestStatus.status = 'rejected';
    this.hrmSandboxService.setTrainingRequestStatus();
  }

  removepostTrainingForm(){
    this.postTrainingForm = '';
  }

  removepostCourseForm(){
    this.postCourseForm = '';
  }

}
