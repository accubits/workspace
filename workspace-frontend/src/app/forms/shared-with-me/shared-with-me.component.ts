import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DataService } from '../../shared/services/data.service';
import { FormsSandbox } from '../forms.sandbox';
import { FormsUtilityService } from '../../shared/services/forms-utility.service';
import { Router, ActivatedRoute } from '@angular/router';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { UtilityService } from '../../shared/services/utility.service';

@Component({
  selector: 'app-shared-with-me',
  templateUrl: './shared-with-me.component.html',
  styleUrls: ['./shared-with-me.component.scss']
})
export class SharedWithMeComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  loadingForm =  false;

  constructor(
    public dataService: DataService,
    public formsUtilityService: FormsUtilityService,
    private spinner: Ng4LoadingSpinnerService,
    private formsSandbox: FormsSandbox,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private utilityService: UtilityService,
  ) { }

  ngOnInit() {
    // Getting all forms
    this.dataService.getAllForms.tab = 'sharedWithMe';
    this.formsSandbox.getAllForms();


  }

  viewSubmitForm(index) {
    if(this.loadingForm)return;
    this.loadingForm = true;
    this.dataService.viewForm.selctedFormSlug = this.dataService.getAllForms.formListsDeatils.forms[index].formSlug;
    this.dataService.viewForm.permission = this.dataService.getAllForms.formListsDeatils.forms[index].permission;
    
    /* when shared user only have view permission */
    if(this.dataService.viewForm.permission ===  'view'){
      this.formsSandbox.viewForm().subscribe((result: any) => {
        this.spinner.hide();
        this.loadingForm = false;
        this.dataService.viewForm.selectedFormDetails = result.data;
        // this.dataService.createForm.title = this.dataService.viewForm.selectedFormDetails.formTitle;
        // this.dataService.createForm.description = this.dataService.viewForm.selectedFormDetails.description;
        // this.dataService.createForm.formSlug = this.dataService.viewForm.selectedFormDetails.formSlug;
        // this.dataService.createForm.action = 'update';
      
        for (let i = 0; i < this.dataService.viewForm.selectedFormDetails.formComponents.length; i++) {
          let obj = {
            index: this.utilityService.generaterandomID(),
            name: this.dataService.viewForm.selectedFormDetails.formComponents[i].type,
            id: '1',
            type: 'item',
            element:this.dataService.viewForm.selectedFormDetails.formComponents[i],
            pagination:{currentPage:0}
          }
          obj.element.action = "update";
          this.dataService.formElementArray.push(obj);
          
        }
        this.dataService.viewForm.previewOnlyShow =  true;
  
         },
        err => {
          this.loadingForm = false;
          console.log(err);
          this.spinner.hide();
        })
    }else{
      this.router.navigate(['../../form_creation', { formSlug: this.dataService.viewForm.selctedFormSlug }], { relativeTo: this.activatedRoute });
    }
   // this.formsSandbox.viewPublishedForm();
  }

  /* Blocking propagation checkbox click */
  selectForm(event): void {
    event.stopPropagation();
  }

    //* sort forms */
    sortForms(sortItem):void{
      this.dataService.getAllForms.sortBy = sortItem
      this.dataService.getAllForms.sortOrder ==='asc' ? this.dataService.getAllForms.sortOrder = 'desc' : this.dataService.getAllForms.sortOrder = 'asc'; 
      this.formsSandbox.getAllForms();
    }


    ngOnDestroy(){
        this.dataService.resetGetAllForms();
      }
}
