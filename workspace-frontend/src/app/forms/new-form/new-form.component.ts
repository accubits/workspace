import { FormsSandbox } from './../forms.sandbox';
import { Component, OnInit } from '@angular/core';
import { DataService } from '../../shared/services/data.service';
import { ToastService } from '../../shared/services/toast.service';
import { Router, ActivatedRoute } from '@angular/router';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';

@Component({
  selector: 'app-new-form',
  templateUrl: './new-form.component.html',
  styleUrls: ['./new-form.component.scss']
})
export class NewFormComponent implements OnInit {
  formSlug:'';

  constructor(
    public dataService: DataService , 
    private activatedRoute: ActivatedRoute, 
    private router: Router,
    private toastService: ToastService,
    private formsSandbox: FormsSandbox,
    private spinner: Ng4LoadingSpinnerService
  ) { }

  ngOnInit() {
  }

  /*Navigating to new form builder[Start] */
  createForm() {

    //this.spinner.show();
    if(!this.dataService.createForm.title || this.dataService.createForm.title === ''){
      this.toastService.Error('','Enter the title');
      return;
    }
    if(!this.dataService.createForm.description || this.dataService.createForm.title === '')
    {
      this.toastService.Error('','Enter the description');
      return;
    }
    
    this.spinner.show();
    this.dataService.createFormPartial.title = this.dataService.createForm.title;
    this.dataService.createFormPartial.description = this.dataService.createForm.description;
    this.dataService.createFormPartial.allowMultiSubmit = this.dataService.createForm.allowMultiSubmit;
    this.dataService.createFormPartial.formStatus = 'draft'

    // Creating form Partially
    this.formsSandbox.createNewFormPartial().subscribe((result: any) => {
      //this.spinner.show();
      if (result.status === 'OK') {
         this.formSlug = result.data.formslug;
         // Navigating to form builder
        //  this.spinner.show();
         this.router.navigate(['../form_creation', { formSlug: this.formSlug }], { relativeTo: this.activatedRoute });
         this.dataService.new_form.show = false;
        }

        if(result.status === 'ERROR'){
          this.spinner.hide();
          this.toastService.Error('Form name should be unique');
        }
      this.dataService.formAPICall.inProgress = false;
  },
    err => {
      this.spinner.hide();
      this.dataService.formAPICall.inProgress = false;
      this.toastService.Error(err.msg);
    });
  
  }
  /*Navigating to new form builder[End] */
}