import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DataService } from '../../shared/services/data.service';
import { FormsUtilityService } from '../../shared/services/forms-utility.service';
import { FormsSandbox } from '../forms.sandbox';
import { Router, ActivatedRoute } from '@angular/router';


@Component({
  selector: 'app-inactive',
  templateUrl: './inactive.component.html',
  styleUrls: ['./inactive.component.scss']
})
export class InactiveComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(
    public dataService: DataService,
    private formsSandbox: FormsSandbox,
    public formsUtilityService: FormsUtilityService,
    private router: Router,
    private activatedRoute: ActivatedRoute
 
  ) { }

  ngOnInit() {
    // Getting all forms
    this.dataService.getAllForms.tab = 'inactive';
    this.formsSandbox.getAllForms();

    
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

   /* Show Shared Users */
   showMoreOption(idx,event):void{
    event.stopPropagation();
    this.dataService.getAllForms.formListsDeatils.forms[idx]['showMore'] =  !this.dataService.getAllForms.formListsDeatils.forms[idx]['showMore'];
    if(!this.dataService.getAllForms.formListsDeatils.forms[idx]['showMore'])return
    // Only for share option

    this.dataService.sendUsers.option = 'inactive';

    this.dataService.formShare.sharedUsers = this.dataService.getAllForms.formListsDeatils.forms[idx].sharedUsers;
    this.dataService.formSend.sendUsers = this.dataService.getAllForms.formListsDeatils.forms[idx].sendUsers;

    this.dataService.formShare.formSlug =  this.dataService.getAllForms.formListsDeatils.forms[idx].formSlug;
    this.dataService.formShare.formTitle =  this.dataService.getAllForms.formListsDeatils.forms[idx].formTitle;
  }

   /* View Selected form for edit */
   viewForm(index): void {
    this.dataService.viewForm.selctedFormSlug = this.dataService.getAllForms.formListsDeatils.forms[index].formSlug;
    this.router.navigate(['../../form_creation', { formSlug: this.dataService.viewForm.selctedFormSlug }], { relativeTo: this.activatedRoute });
  }


  ngOnDestroy(){
    this.dataService.resetGetAllForms();
  }

shareOption(idx, event){
// this.dataService.sendToOption.show =true;
// this.dataService.sendUsers.option = 'inactive';

// this.dataService.formShare.formTitle = form.formTitle;
// this.dataService.formShare.sharedUsers = form.sharedUsers;
// this.dataService.formSend.sendUsers = form.sendUsers

// console.log('Tevfv',form.sendUsers);
// this.dataService.sendUsers.sendUserList = form.sendUsers;
// this.dataService.formShare.formSlug = form.formSlug;
  event.stopPropagation();
  this.dataService.sendToOption.show = !this.dataService.sendToOption.show;
  this.dataService.sendUsers.option = 'inactive';
  this.dataService.formSend.sendUsers = this.dataService.getAllForms.formListsDeatils.forms[idx].sendUsers;
  this.dataService.formShare.formSlug = this.dataService.getAllForms.formListsDeatils.forms[idx].formSlug;
}

}
