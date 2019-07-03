import { Component, OnInit, ViewChild } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { DataService } from '../../shared/services/data.service';
import { FormsSandbox } from '../forms.sandbox';
import { FormPreviewComponent } from '../form-preview/form-preview.component';
import { UtilityService } from '../../shared/services/utility.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';



@Component({
  selector: 'app-form-listing-wrap',
  templateUrl: './form-listing-wrap.component.html',
  styleUrls: ['./form-listing-wrap.component.scss']
})
export class FormListingWrapComponent implements OnInit {
  @ViewChild(FormPreviewComponent) childpreview: FormPreviewComponent;


  constructor(
     private router: Router,
     private activatedRoute: ActivatedRoute,
     public dataService: DataService,
     public formsSandbox: FormsSandbox,
     private utilityService: UtilityService,
     private spinner: Ng4LoadingSpinnerService,


    ) { }

  ngOnInit() {
    this.formsSandbox.getUserList();
    this.formsSandbox.getCountry();
  }
  closeOverlay(){
    this.dataService.confirmPop.show = false
  }
  /* Show new form builder */
  showNewForm():void{
    this.dataService.resetForm();
    this.dataService.new_form.show =  true;
  }

  /* Delete Form */
  deleteForm():void{
    this.dataService.formsSelectionManagement.selectedFormSlugs.push(this.dataService.formShare.formSlug);
     this.formsSandbox.deleteForms();
  }

  editSelectedForm():void{
     this.dataService.formAdminmodal.show =  false;
     this.router.navigate(['../form_creation', { formSlug: this.dataService.viewForm.selctedFormSlug }], { relativeTo: this.activatedRoute });
   
  }

  /* Unpublishing a form[Start] */
   confirmPublishForm():void{
     this.dataService.viewForm.updatedStatus = 'inactive' ;
     this.dataService.viewForm.unPublishmodal.show =  true;
  }

  unPublishForm():void{
     this.formsSandbox.updateFormStatus();
  }
  /* Unpublishing a form[End] */

  cancelUnpublished():void{
    this.dataService.viewForm.unPublishmodal.show =  false;
  }

  /* close Response view */
  closeResponseView():void{
    this.dataService.formAdminmodal.show = false;
    this.dataService.resetFormResponse();
  }

  showPreview(){
    this.dataService.form_preview_modal.show = true;
    this.formsSandbox.viewForm().subscribe((result: any) => {
      this.dataService.viewForm.selectedFormDetails = result.data;
      this.dataService.createForm.title = this.dataService.viewForm.selectedFormDetails.formTitle;
      this.dataService.createForm.description = this.dataService.viewForm.selectedFormDetails.description;
      this.dataService.createForm.formSlug = this.dataService.viewForm.selectedFormDetails.formSlug;
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
       
  }

