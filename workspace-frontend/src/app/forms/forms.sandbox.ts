import { Injectable } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { Location } from '@angular/common';
import merge from 'deepmerge'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Observable } from 'rxjs/Observable';
import "rxjs/add/operator/share";
import { DataService } from '../shared/services/data.service';
import { ToastService } from '../shared/services/toast.service';
import { FormsApiService } from '../shared/services/forms-api.service';
import { FormsUtilityService } from '../shared/services/forms-utility.service';
import { TaskApiService } from '../shared/services/task-api.service';
import { TaskDataService } from '../shared/services/task-data.service';
import { AngularDateTimePickerModule } from 'angular2-datetimepicker';
import { ActivityApiService } from '../shared/services/activity-api.service';
import { ActStreamDataService } from '../shared/services/act-stream-data.service';

@Injectable()
export class FormsSandbox {


  constructor(
    public actStreamDataService: ActStreamDataService,
    public dataService: DataService,
    public activityApiService: ActivityApiService,
    private toastService: ToastService,
    private formsApiService: FormsApiService,
    private formsUtilityService: FormsUtilityService,
    private taskApiService: TaskApiService,
    public taskDataService: TaskDataService,
    private spinner: Ng4LoadingSpinnerService,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private location: Location

  ) { }

  /* Sandbox to handle API call for Creating Form[Start] */
  createNewForm() {
    this.spinner.show();
    this.dataService.formAPICall.inProgress = true;
    // Accessing forms API service
    return this.formsApiService.createNewForm().subscribe((result: any) => {

      if (result.status === 'OK') {
        if (this.dataService.createForm.formStatus === 'active') {
          this.dataService.publish_form.show = true;
          this.dataService.formSend.formSlug = result.data.formslug;
          this.dataService.formSend.sendUsers = this.dataService.viewForm.selectedFormDetails.sendUsers;
          this.dataService.resetForm();
          this.spinner.hide();
          this.toastService.Success('', 'form successfully created')
        }

        if (this.dataService.createForm.formStatus === 'draft') {
          this.toastService.Success('', 'Form successfully drafted')
          this.formsUtilityService.formDraftedConfirmation();
        }

      } else {
        this.toastService.Error('', result.error.msg);
      }
      this.dataService.formAPICall.inProgress = false;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error('', err.msg)
        this.dataService.formAPICall.inProgress = false;
      })
  }
  /* Sandbox to handle API call for Creating Form[End] */


  formCreation() {
    this.spinner.show();
    this.dataService.formAPICall.inProgress = true;
    // Accessing forms API service
    return this.formsApiService.createNewForm().subscribe((result: any) => {
      this.dataService.publish_form.show = true;
      this.dataService.formSend.formSlug = result.data.formslug;
      this.dataService.formSend.sendUsers = this.dataService.viewForm.selectedFormDetails.sendUsers;
      this.dataService.resetForm();
      this.spinner.show();
      this.toastService.Success('', 'form successfully created');
      this.spinner.hide();
      this.dataService.formAPICall.inProgress = false;
      // this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg)
        this.spinner.hide();
        this.dataService.formAPICall.inProgress = false;
      })
  }
  /* Sandbox to handle API call for Creating Form[End] */


  /* Sandbox to handle API call for saving Form[Start] */
  saveForm() {
    this.spinner.show();
    this.dataService.formAPICall.inProgress = true;
    // Accessing forms API service
    return this.formsApiService.createNewForm().subscribe((result: any) => {

      if (result.status === 'OK') {
        if (this.dataService.createForm.formStatus === 'active') {
          //this.dataService.publish_form.show = true;
          this.dataService.formSend.formSlug = result.data.formslug;
          this.dataService.formSend.sendUsers = this.dataService.viewForm.selectedFormDetails.sendUsers;
          this.dataService.resetForm();
          this.toastService.Success('', 'form successfully created')

        }

        if (this.dataService.createForm.formStatus === 'draft') {
          this.toastService.Success('', 'Form successfully drafted')
          this.formsUtilityService.formDraftedConfirmation();
        }

      } else {
        this.toastService.Error('', result.error.msg);
        //let temp = this.dataService.createForm.formComponents;
      }
      this.dataService.formAPICall.inProgress = false;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error('', err.msg)
        this.spinner.hide();
        this.dataService.formAPICall.inProgress = false;
      })
  }
  /* Sandbox to handle API call for saving Form[End] */



  /* Sandbox to handle API call for Creating Form[Start] */
  draftNewForm() {
    // this.spinner.show();
    this.dataService.formAPICall.inProgress = true;
    // Accessing forms API service
    return this.formsApiService.createNewForm().share()
  }
  /* Sandbox to handle API call for Creating Form[End] */

  /* Sandbox to handle API call for Creating Form Partially[Start] */
  createNewFormPartial() {
    this.spinner.show();
    this.dataService.formAPICall.inProgress = true;
    // Accessing forms API service

    return this.formsApiService.createNewFormPartial().share();
  }
  /* Sandbox to handle API call for Creating Form[End] */

  /* Sandbox to handle API call for getting All Form[Start] */
  getAllForms() {
    this.spinner.show();
    this.dataService.resetForm();
    this.dataService.resetFormSelectionManagement();
    // Accessing forms API service
    return this.formsApiService.getAllForms().subscribe((result: any) => {
      this.dataService.getAllForms.formListsDeatils = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting all Forms[End] */

  /* Sandbox to handle API call get countries [Start] */
  getCountry() {
    this.dataService.resetForm();
    this.spinner.show();
    // Accessing forms API service
    return this.formsApiService.getAllCountries().subscribe((result: any) => {
      this.dataService.countryTemplate.countries = result.data.countries;
    },
      err => {
        console.log(err);

      })
  }


  /* Sandbox to handle API call get countries [End] */



  /* Sandbox to handle API call for view a Form[Start] */
  viewForm() {
    this.dataService.resetForm();
    this.spinner.show();
    // Accessing forms API service
    return this.formsApiService.viewForm().share();
  }
  /* Sandbox to handle API call for view a Form[End] */

  /* Sandbox to handle API call for view a published Form[Start] */
  viewPublishedForm() {
    this.dataService.resetForm();
    this.spinner.show();
    // Accessing forms API service
    return this.formsApiService.viewPublishedForm().subscribe((result: any) => {
      this.dataService.viewForm.selectedFormDetails = result.data;
      this.dataService.viewForm.pageSlugs = Object.keys(this.dataService.viewForm.selectedFormDetails.formPages);
      this.dataService.viewForm.selectdPageSlug = this.dataService.viewForm.pageSlugs[0];
      this.dataService.viewForm.selectedPage[this.dataService.viewForm.selectdPageSlug] = this.dataService.viewForm.selectedFormDetails.formPages[this.dataService.viewForm.selectdPageSlug].formComponents

      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for view a Form[End] */

  /* Sandbox to handle API call for view a published Form[Start] */
  viewPublishedFormWithAns() {
    this.dataService.resetForm();
    this.spinner.show();
    // Accessing forms API service
    return this.formsApiService.viewPublishedFormWithAns().subscribe((result: any) => {
      this.dataService.viewForm.selectedFormDetails = result.data;
      this.dataService.viewForm.pageSlugs = Object.keys(this.dataService.viewForm.selectedFormDetails.formPages);
      this.dataService.viewForm.selectdPageSlug = this.dataService.viewForm.pageSlugs[0];
      this.dataService.viewForm.selectedPage[this.dataService.viewForm.selectdPageSlug] = this.dataService.viewForm.selectedFormDetails.formPages[this.dataService.viewForm.selectdPageSlug].formComponents

      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for view a Form[End] */

  /* Fetch Selected activity Details[Start] */
  fetchActivityStream() {
    this.spinner.show();
    // Accessing task API service
    return this.activityApiService.fetchActivityStream().subscribe((result: any) => {
      this.actStreamDataService.fetchActivityStream.streamData = result.data.streamData;
      this.actStreamDataService.fetchActivityStream.total = result.data.total;
      this.actStreamDataService.fetchActivityStream.to = result.data.to;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Fetch Selected activity Details[End] */

  /* Sandbox to handle API call for view a submit Form[Start] */
  submitFormResponse() {
    this.spinner.show();
    // Accessing forms API service
    return this.formsApiService.submitFormResponse().subscribe((result: any) => {
      this.dataService.formAPICall.inProgress = false;
      this.getAllForms();
      this.dataService.resetFormView();
      this.actStreamDataService.fetchActivityStream.page = 1;
      this.actStreamDataService.fetchActivityStream.perPage = 10;
      this.actStreamDataService.fetchActivityStream.selectedTab = 'recent';
      this.fetchActivityStream();
      if (result.status == 'OK') {
        this.toastService.Success('', 'Form submitted succesfully');
        this.spinner.hide();
        this.location.back();
      }
      else if (result.status == 'ERROR') {
        this.toastService.Error('', result.error.msg);
      }
    },
      err => {
        this.dataService.formAPICall.inProgress = false;
        console.log(err);
        this.dataService.resetFormView();
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for submit Form[End] */



  /* Sandbox to handle API call for getting  user list[Start] */
  getUserList() {
    this.spinner.show();
    // Accessing forms API service
    return this.taskApiService.getResponsiblePersons().subscribe((result: any) => {
      this.taskDataService.responsiblePersons.list = result.data;
      this.spinner.hide();
      /* Hiding Selected users from user list */
      if (this.dataService.formShare.sharedUsers.length > 0) {
        for (let i = 0; i < this.dataService.formShare.sharedUsers.length; i++) {
          let selUserinList = this.taskDataService.responsiblePersons.list.filter(
            user => user.slug === this.dataService.formShare.sharedUsers[i].userSlug)[0];
          if (selUserinList) {
            let idx = this.taskDataService.responsiblePersons.list.indexOf(selUserinList)
            this.taskDataService.responsiblePersons.list[idx]['existing'] = true;
          }

        }

      }


    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting  user list[End] */

  /* Sandbox to handle API call for getting  share form[Start] */
  shareForm() {
    this.spinner.show();
    // Accessing forms API service
    return this.formsApiService.shareForm().subscribe((result: any) => {
      if (result.status === 'OK') {
        // if (this.dataService.publish_form.show) {
        //   this.dataService.publish_form.show = false;
        //   this.location.back();
        // }
        this.dataService.shareOption.show = false;
        if(this.dataService.sharedUsers.option === 'publish'){
          this.dataService.getAllForms.tab = 'published';
          this.getAllForms();
          this.toastService.Success(result.data.msg);
          this.dataService.resetForm();
          this.dataService.resetFormshare();
        }
        if(this.dataService.sharedUsers.option === 'draft'){
          this.dataService.getAllForms.tab = 'draft';
          this.getAllForms();
          this.toastService.Success(result.data.msg);
          this.dataService.resetForm();
          this.dataService.resetFormshare();
        }
      
      } else {
        this.toastService.Error(result.error.msg)
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting  user list[End] */

  /* Sandbox to handle API call for send Form[Start] */
  sendForm() {
    this.spinner.show();
    // Accessing forms API service
    return this.formsApiService.sendForm().subscribe((result: any) => {
      if (result.status === 'OK') {
        if (this.dataService.publish_form.show) {
          this.dataService.publish_form.show = false;
          this.location.back();
        }
        this.dataService.shareOption.show = false;
        this.dataService.sendToOption.show = false;
        this.dataService.formSend.sendUsers = [];
        this.dataService.sendUsers.sendUserList = [];
        this.spinner.show();
        if(this.dataService.sendUsers.option === 'publish'){
          this.dataService.getAllForms.tab = 'published';
          this.getAllForms();
          this.toastService.Success(result.data.msg);
          this.dataService.resetForm();
          this.dataService.resetFormSend();
        }
        if(this.dataService.sendUsers.option === 'inactive'){
          this.dataService.getAllForms.tab = 'inactive';
          this.getAllForms();
          this.toastService.Success('', result.data.msg);
          this.dataService.resetForm();
          this.dataService.resetFormSend();
        }
       
      } else {
        this.toastService.Error('', result.error.msg)
      }

      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for send Form[Start] */

  /* Sandbox to handle API call for getting  share form[Start] */
  getAllFormRespose() {
    this.spinner.show();
    // Accessing forms API service
    return this.formsApiService.getAllFormRespose().subscribe((result: any) => {
      this.spinner.hide();
      this.dataService.formResponseManagement.reponseDetails = result.data;
      if (this.dataService.formResponseManagement.reponseDetails['total'] > 0) {
        this.dataService.formResponseManagement.selectedAnswerSlug = this.dataService.formResponseManagement.reponseDetails['formResponses'][0].answersheetSlug;
        this.getsSingleFormResponseDetails();
      }

    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting  user list[End] */


  /* Sandbox to handle API call for getting  single form response details[Start] */
  getsSingleFormResponseDetails() {
    this.spinner.show();
    // Accessing forms API service
    return this.formsApiService.getSingleFormRespponseDetails().subscribe((result: any) => {
      this.spinner.hide();
      this.dataService.formResponseManagement.selectedResponseDetails = result.data;
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting  single form response details[End] */



  /* Sandbox to handle API call fordelete Forms[Start] */
  deleteForms() {
    this.spinner.show();

    // Accessing forms API service
    return this.formsApiService.deleteForms().subscribe((result: any) => {
      this.getAllForms();
      this.spinner.hide();
      this.toastService.Success('', 'Deleted forms succesfully');
      this.dataService.confirmPop.show = false
      this.dataService.formsSelectionManagement.selectedFormSlugs = [];
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call fordelete Forms[End] */


  /* Sandbox to handle API call Update from status[Start] */
  updateFormStatus() {
    this.spinner.show();

    // Accessing forms API service
    return this.formsApiService.updateFormStatus().subscribe((result: any) => {
      this.dataService.viewForm.unPublishmodal.show = false;
      this.getAllForms();
      this.spinner.hide();
      this.toastService.Success('', 'Form status updated successfully');
      this.dataService.confirmPop.show = false;
      this.dataService.formAdminmodal.show = false
      this.dataService.formsSelectionManagement.selectedFormSlugs = [];
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call Update from status[End] */
}
