import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from './../hrm.sandbox';
import { DataService } from './../../shared/services/data.service'

@Component({
  selector: 'app-feedback',
  templateUrl: './feedback.component.html',
  styleUrls: ['./feedback.component.scss']
})
export class FeedbackComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService:HrmSandboxService,
    public dataService:DataService

  ) { }
  trainingEvaluation = false;
  isValidated = false;
  courseEvaluation = false;
  moreOption = false;
  isValidateOngoing = false;
  moreOptionFirst = false;
  ngOnInit() {
    // this.hrmSandboxService.getFeedbackForm();
    this.hrmDataService.getAllForms.tab = 'active';
        this.hrmDataService.getAllForms.type = 'postCourseFeedbackForm';
        this.hrmSandboxService.getCourseForm();
        this.hrmDataService.getAllForms.type = 'postTrainingFeedbackForm';
        this.hrmSandboxService.getFeedbackForm();
    this.trainingEvaluation = true;
  }

  showTrainingEvaluation() {
    this.trainingEvaluation = !this.trainingEvaluation;
    this.isValidated = !this.isValidated;
    this.courseEvaluation =false;
  }
  showCourseEvaluation() {
    this.courseEvaluation  = !this.courseEvaluation;
    this.isValidateOngoing = !this.isValidateOngoing;
    this.trainingEvaluation = false;
  }
  showMoreOption() {
    this.moreOption = true;
  }
  hideMoreOption() {
    this.moreOption = false;
  }
  showMoreOptionFirst() {
    this.moreOptionFirst = true;
  }
  hideMoreOptionFirst() {
    this.moreOptionFirst = false;
  }
  showFormCreationBlock(){
    this.dataService.createFormPartial.formAccessType = 'postTrainingFeedbackForm';
    this.hrmDataService.newFormCreation.show = true;
    this.dataService.saveHrm.show = true;
  }

  showPostCourseFormBlock(){
    this.hrmDataService.newFormCreation.show = true;
    this.dataService.saveCourseHrm.show = true;
    this.dataService.createFormPartial.formAccessType = 'postCourseFeedbackForm';
  }
}
