import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DataService } from '../../shared/services/data.service';
import { FormsUtilityService } from '../../shared/services/forms-utility.service';
import { FormsSandbox } from '../forms.sandbox';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-drafts',
  templateUrl: './drafts.component.html',
  styleUrls: ['./drafts.component.scss']
})
export class DraftsComponent implements OnInit {

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
    this.dataService.getAllForms.tab = 'draft';
    this.formsSandbox.getAllForms();
    console.log('kcakccc',this.dataService.getAllForms.formListsDeatils)
  }

  /* View Selected form for edit */
  viewForm(index): void {
    this.dataService.viewForm.selctedFormSlug = this.dataService.getAllForms.formListsDeatils.forms[index].formSlug;
    this.router.navigate(['../../form_creation', { formSlug: this.dataService.viewForm.selctedFormSlug }], { relativeTo: this.activatedRoute });
  }

  /* Blocking propagation checkbox click */
  selectForm(event): void {
    event.stopPropagation();
  }

  //* sort forms */
  sortForms(sortItem): void {
    this.dataService.getAllForms.sortBy = sortItem
    this.dataService.getAllForms.sortOrder === 'asc' ? this.dataService.getAllForms.sortOrder = 'desc' : this.dataService.getAllForms.sortOrder = 'asc';
    this.formsSandbox.getAllForms();
  }
  /* Show Shared Users */
  showMoreOption(idx, event): void {
    event.stopPropagation();
    this.dataService.getAllForms.formListsDeatils.forms[idx]['showMore'] = !this.dataService.getAllForms.formListsDeatils.forms[idx]['showMore'];
    if (!this.dataService.getAllForms.formListsDeatils.forms[idx]['showMore']) return
    this.dataService.sharedUsers.option = 'draft';
    this.dataService.formShare.sharedUsers = this.dataService.getAllForms.formListsDeatils.forms[idx].sharedUsers;
    this.dataService.formShare.formSlug = this.dataService.getAllForms.formListsDeatils.forms[idx].formSlug;
    this.dataService.formShare.formTitle = this.dataService.getAllForms.formListsDeatils.forms[idx].formTitle;
  }

  /* Show Shared Users */
  showSharedUsers(idx, event): void {
    event.stopPropagation();
    this.dataService.shareOption.show = !this.dataService.shareOption.show;
    this.dataService.sharedUsers.option = 'draft';
    this.dataService.formShare.sharedUsers = this.dataService.getAllForms.formListsDeatils.forms[idx].sharedUsers;
    this.dataService.formShare.formSlug = this.dataService.getAllForms.formListsDeatils.forms[idx].formSlug;
  }

  ngOnDestroy() {
    this.dataService.resetGetAllForms();
  }
}
