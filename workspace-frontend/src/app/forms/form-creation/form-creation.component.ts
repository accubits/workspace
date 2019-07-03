import { Component, OnInit, HostListener, ViewChild, Inject, ElementRef} from '@angular/core';
import { DOCUMENT } from '@angular/platform-browser';
import { Configs } from '../../config';
import { ContentWrapRightComponent } from '../content-wrap-right/content-wrap-right.component';
import { FormPreviewComponent } from '../form-preview/form-preview.component';
import { DataService } from '../../shared/services/data.service';
import { FormsSandbox } from '../forms.sandbox';
import { UtilityService } from '../../shared/services/utility.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Router, ActivatedRoute } from '@angular/router';
import { FormsUtilityService } from '../../shared/services/forms-utility.service';
import { ToastService } from '../../shared/services/toast.service';

@Component({
  selector: 'app-form-creation',
  templateUrl: './form-creation.component.html',
  styleUrls: ['./form-creation.component.scss']
})
export class FormCreationComponent implements OnInit {
  @ViewChild(ContentWrapRightComponent) child: ContentWrapRightComponent;
  @ViewChild(FormPreviewComponent) childpreview: FormPreviewComponent;
  
  newElement: any;
  public assetUrl = Configs.assetBaseUrl;
  activeRpTab: string;
  currentRoute: string;

  constructor(
    public dataService: DataService,
    public formsSandbox: FormsSandbox,
    private utilityService: UtilityService,
    private spinner: Ng4LoadingSpinnerService,
    private route: ActivatedRoute,
    public formsUtilityService: FormsUtilityService,
    public toastService: ToastService,
    private router: Router

  ) {
    this.activeRpTab = 'recent';
    this.router.events.subscribe(() => {
      this.currentRoute = this.router.url.split('/')[2];
    });
  }

  elementSelect(id) {
    this.child.pushElement(id);
    this.newElement = id;
  }





  ngOnInit() {
    this.dataService.resetForm();
    const clientHeight = document.getElementById('panel-heading').offsetHeight;
    this.route.params
      .subscribe(params => {
        this.dataService.viewForm.selctedFormSlug = params.formSlug;

      });
    if (this.dataService.viewForm.selctedFormSlug)
      this.loadForm();
    this.formsSandbox.getUserList()

    if (this.currentRoute == 'hrm') {
      this.dataService.saveHrm.show = true;
      this.dataService.saveCourseHrm.show = true;
      this.dataService.createForm.formAccessType = this.dataService.createFormPartial.formAccessType;
    }
    else {
      this.dataService.saveHrm.show = false;
      this.dataService.saveCourseHrm.show = false;
    }

  }

  /* loading Form for edit[Start] */
  loadForm(): void {
    // this.spinner.show();
    this.formsSandbox.viewForm().subscribe((result: any) => {
      this.dataService.viewForm.selectedFormDetails = result.data;
      this.dataService.createForm.title = this.dataService.viewForm.selectedFormDetails.formTitle;
      this.dataService.createForm.description = this.dataService.viewForm.selectedFormDetails.description;
      this.dataService.createForm.formSlug = this.dataService.viewForm.selectedFormDetails.formSlug;
      this.dataService.createForm.allowMultiSubmit = this.dataService.viewForm.selectedFormDetails.allowMultiSubmit === 1 ? true : false;
      this.dataService.createForm.action = 'update';

      for (let i = 0; i < this.dataService.viewForm.selectedFormDetails.formComponents.length; i++) {
        let obj = {
          index: this.utilityService.generaterandomID(),
          name: this.dataService.viewForm.selectedFormDetails.formComponents[i].type,
          id: '1',
          type: 'item',
          element: this.dataService.viewForm.selectedFormDetails.formComponents[i],
          pagination: { currentPage: 0 }
        }
        obj.element.action = "update";
        this.dataService.formElementArray.push(obj);
        this.spinner.hide();
      }

    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* loading Form for edit[End] */

  /* Validating new form[Start] */
  validateNewForm(): any {
    // this.spinner.hide();
    this.spinner.show();
    this.dataService.formValidation.validating = true;
    if (this.dataService.formElementArray.length === 0) {
      this.toastService.Error('', 'Form should have atleast one component');
      this.spinner.hide();

      return false;
    }

    for(let i=0;i<this.dataService.formElementArray.length;i++){
        if((this.dataService.formElementArray.length === 1 ) && (this.dataService.formElementArray[i].name === "page" )){
          this.toastService.Error('', 'Form should have atleast one basic or fancy component');
          this.spinner.hide();
          return false;
        
      }
    }

    let inValidElement = this.dataService.formElementArray.filter(
      component => component.element.isValidated === false)[0];

    if (inValidElement) {
      this.dataService.formElementToggle.activeIndex = inValidElement.index;
      this.formsUtilityService.focusInvalidElement(inValidElement.index)
      return false;
    }

    this.dataService.formValidation.validating = false;
    return true;
  }
  /* Validating new form[End] */

  /* Creating a new form[Start] */
  createOrDraftNewForm(option): void {
    this.dataService.createForm.formAccessType = "internal";
    if (!this.validateNewForm()) return;
    this.dataService.formAPICall.inProgress = true;
    this.dataService.createForm.formComponents = [];
   
    for (let i = 0; i < this.dataService.formElementArray.length; i++) {
      this.dataService.createForm.formComponents.push(this.dataService.formElementArray[i].element);
    }
    this.dataService.createForm.isPublished = false;
    this.dataService.createForm.formStatus = 'draft';
    
    this.spinner.show();
    if(option === 'draft'){
      this.draftNewForm()
    }
    else{
      this.formsSandbox.formCreation();
    }
  }
  /* Creating a new form[End] */



  /* Saving a new form[Start] */
  saveForm(fromStatus): void {
    this.dataService.createForm.formStatus = fromStatus;
    this.dataService.createForm.formAccessType = "postTrainingFeedbackForm";
    if (!this.validateNewForm()) return;
    this.dataService.formAPICall.inProgress = true;
    this.dataService.createForm.formComponents = [];
    for (let i = 0; i < this.dataService.formElementArray.length; i++) {
      this.dataService.createForm.formComponents.push(this.dataService.formElementArray[i].element);
    }
    if (this.dataService.createForm.formStatus === 'active') {
      this.spinner.show();
      this.formsSandbox.saveForm();
      this.dataService.saveHrm.show = false;
    } else {
      this.draftNewForm()
    }
  }
  /* Saving a new form[End] */

  /* Saving a new form[Start] */
  saveHrmForm(fromStatus): void {
    this.dataService.createForm.formStatus = fromStatus;
    if (!this.validateNewForm()) return;
    this.dataService.formAPICall.inProgress = true;
    this.dataService.createForm.formComponents = [];
    for (let i = 0; i < this.dataService.formElementArray.length; i++) {
      this.dataService.createForm.formComponents.push(this.dataService.formElementArray[i].element);
    }
    if (this.dataService.createForm.formStatus === 'active') {
      this.formsSandbox.saveForm();
      this.dataService.saveHrm.show = false;
    } else {
      this.draftNewForm()
    }
  }
  /* Saving a new form[End] */

  /* Drafting a new form[End] */
  draftNewForm(): void {
    // this.spinner.show();
    this.formsSandbox.draftNewForm().subscribe((result: any) => {
      if (result.status === 'OK') {
        if (this.dataService.createForm.formStatus === 'draft') {
          this.spinner.show();
          this.toastService.Success('', 'Form successfully drafted');
          this.spinner.hide();
          this.loadForm();
          this.dataService.resetForm();
          setTimeout(() => {
            // this.loadForm();
          }, 300);
        }
      }
      else {
        this.toastService.Error('', result.error.msg);
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error('', err.msg)
        this.spinner.hide();
        this.dataService.formAPICall.inProgress = false;
      })
  }
  /* Drafting a new form[End] */
}


