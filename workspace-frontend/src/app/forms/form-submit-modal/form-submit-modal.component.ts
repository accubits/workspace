import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { Configs } from '../../config';
import { DataService } from '../../shared/services/data.service';
import { FormsSandbox } from '../forms.sandbox';
import { HrmDataService } from '../../shared/services/hrm-data.service'
import { ActivatedRoute } from '@angular/router';


@Component({
  selector: 'app-form-submit-modal',
  templateUrl: './form-submit-modal.component.html',
  styleUrls: ['./form-submit-modal.component.scss']
})
export class FormSubmitModalComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  public slug;
  localElementArray: any = [];
  constructor(
    public dataService: DataService,
    public formsSandbox: FormsSandbox,
    private route: ActivatedRoute,
    private location: Location,
    public hrmDataService: HrmDataService
  ) { }

  ngOnInit() {
    this.route.params
      .subscribe(params => {
        this.dataService.viewForm.selctedFormSlug = params.formSlug;
        this.dataService.viewForm.selctedAnsSlug = params.ansSlug;

      });
     this.slug= this.route.snapshot.queryParamMap.get('slug');
     this.dataService.viewForm.selctedAnsSlug !== 'null'?this.formsSandbox.viewPublishedFormWithAns():this.formsSandbox.viewPublishedForm();
  }

  /* Closing form submit modal[start] */
  closeFormSubmit(): void {
    this.dataService.resetFormView();
    this.location.back();

  }
  /* Closing form submit modal[end] */

  validateFormSubmit(element): any {
    this.dataService.formValidation.validating =  true;
    if(!element.isValidated || !element.isValidFormat){
      return false;
    }

    return true;     
  }

  /* submiting form response[start] */
  submitFormResponse(): void {
    this.dataService.viewForm.formResponse.trainingRequestSlug = this.slug;
    if(this.dataService.showDate.show){
      return;
    }
    // Inserting in to form response array
    if (this.dataService.formAPICall.inProgress) return;
    this.dataService.formAPICall.inProgress = true;
    this.dataService.viewForm.formResponse.formComponents = [];
  
    for (let i = 0; i < this.dataService.viewForm.pageSlugs.length; i++) {
      for (let j = 0; j < this.dataService.viewForm.selectedPage[this.dataService.viewForm.pageSlugs[i]].length; j++) {
        if (this.dataService.viewForm.selectedPage[this.dataService.viewForm.pageSlugs[i]][j].type !== 'page' &&
          this.dataService.viewForm.selectedPage[this.dataService.viewForm.pageSlugs[i]][j].type !== 'section')
          if (this.validateFormSubmit(this.dataService.viewForm.selectedPage[this.dataService.viewForm.pageSlugs[i]][j])) {
            this.dataService.viewForm.formResponse.formComponents.push(this.dataService.viewForm.selectedPage[this.dataService.viewForm.pageSlugs[i]][j].elementToSubmit);
          } else {
            this.dataService.formAPICall.inProgress = false;
            return;
          }
       }

    }

    //  if(!this.validateFormSubmit()){
    //   this.dataService.viewForm.formResponse.formComponents = []; // clearing form response
    //   return;
    // };
    this.dataService.formValidation.validating =  false; 

    this.formsSandbox.submitFormResponse();

  }
  /* submiting form response[start] */

  goToPrevious():void{
    let index = this.dataService.viewForm.pageSlugs.indexOf(this.dataService.viewForm.selectdPageSlug);
    this.dataService.viewForm.selectdPageSlug = this.dataService.viewForm.pageSlugs[index - 1];
    this.dataService.viewForm.selectedPage[this.dataService.viewForm.selectdPageSlug] = this.dataService.viewForm.selectedFormDetails.formPages[this.dataService.viewForm.selectdPageSlug].formComponents
 

  }

  goToNext():void{
    let index = this.dataService.viewForm.pageSlugs.indexOf(this.dataService.viewForm.selectdPageSlug);
    this.dataService.viewForm.selectdPageSlug = this.dataService.viewForm.pageSlugs[index + 1];
    this.dataService.viewForm.selectedPage[this.dataService.viewForm.selectdPageSlug] = this.dataService.viewForm.selectedFormDetails.formPages[this.dataService.viewForm.selectdPageSlug].formComponents
  }

}
