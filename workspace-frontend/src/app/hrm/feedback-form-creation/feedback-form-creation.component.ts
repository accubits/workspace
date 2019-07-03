import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { DataService } from '../../shared/services/data.service';
import { FormsSandbox } from '../../forms/forms.sandbox';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { ToastService } from '../../shared/services/toast.service';
import { HrmDataService} from '../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox'

@Component({
  selector: 'app-feedback-form-creation',
  templateUrl: './feedback-form-creation.component.html',
  styleUrls: ['./feedback-form-creation.component.scss']
})
export class FeedbackFormCreationComponent implements OnInit {
  formSlug:'';
  constructor(
    private router: Router,
    private activatedRoute: ActivatedRoute,
    public dataService: DataService , 
    private toastService: ToastService,
    private formsSandbox: FormsSandbox,
    private spinner: Ng4LoadingSpinnerService,
    public hrmDataService: HrmDataService,
    public hrmSandboxService:HrmSandboxService
  ) { }

  ngOnInit() {
  }
  createForm(){
    console.log('dsaf', this.dataService.createFormPartial.formAccessType)
    // this.dataService.createForm.formAccessType = this.dataService.createFormPartial.formAccessType;
   if(!this.dataService.createForm.title || this.dataService.createForm.title === ''){
      this.toastService.Error('','Enter form title');
      return;
    }

    if(!this.dataService.createForm.description  || this.dataService.createForm.description === '' || this.dataService.createForm.description.trim().length === 0){
      this.toastService.Error('','Enter form description');
      return;
    }
    this.spinner.show();
    this.dataService.createFormPartial.title = this.dataService.createForm.title;
    this.dataService.createFormPartial.description = this.dataService.createForm.description;
    this.dataService.createFormPartial.allowMultiSubmit = this.dataService.createForm.allowMultiSubmit;
    this.dataService.createFormPartial.formStatus = 'active';
    // this.dataService.createFormPartial.formAccessType = 'postTrainingFeedbackForm';

    // Creating form Partially

    
    this.formsSandbox.createNewFormPartial().subscribe((result: any) => {
      this.spinner.hide();
      if (result.status === 'OK') {
         this.formSlug = result.data.formslug;
         // Navigating to form builder
         this.router.navigate(['authorized/hrm/form_creation', { formSlug: this.formSlug }]);
        //  this.dataService.saveHrm.show = true;
         this.dataService.frmBtn.show = false;
         this.hrmDataService.newFormCreation.show = false;

    this.hrmDataService.getAllForms.type = 'postTrainingFeedbackForm';
    this.hrmSandboxService.getAllForms();
        }
      this.dataService.formAPICall.inProgress = false;
     },
      err => {
        this.spinner.hide();
        this.dataService.formAPICall.inProgress = false;
        this.toastService.Error('', err.msg);
      });
    // this.router.navigate(['../form_creation'],{ relativeTo: this.activatedRoute });
  }
}
