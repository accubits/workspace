import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DataService } from '../../shared/services/data.service';
import { FormsSandbox } from '../forms.sandbox';
import { FormsUtilityService } from '../../shared/services/forms-utility.service';
import { Router, ActivatedRoute } from '@angular/router';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { UtilityService } from '../../shared/services/utility.service';

@Component({
  selector: 'app-published',
  templateUrl: './published.component.html',
  styleUrls: ['./published.component.scss']
})
export class PublishedComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(
    public dataService: DataService,
    public formsUtilityService: FormsUtilityService,
    private formsSandbox: FormsSandbox,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private spinner: Ng4LoadingSpinnerService,
    private utilityService: UtilityService,
  ) { }

  ngOnInit() {
    this.dataService.getAllForms.tab = 'published';
    this.formsSandbox.getAllForms();
  }

  /* View Selected form for edit */
  viewForm(index): void {
    this.dataService.viewForm.selctedFormSlug = this.dataService.getAllForms.formListsDeatils.forms[index].formSlug;
    this.dataService.formAdminmodal.show = true;
    this.formsSandbox.getAllFormRespose();
  }

  /* Show Shared Users */
  showSharedUsers(idx, event): void {
    event.stopPropagation();
    this.dataService.sendToOption.show = !this.dataService.sendToOption.show;
    this.dataService.sendUsers.option = 'publish';
    // this.dataService.sharedUsers.sharedUserList = this.dataService.getAllForms.formListsDeatils.forms[idx].sharedUsers;

    // this.dataService.sendUsers.sendUserList = this.dataService.getAllForms.formListsDeatils.forms[idx].sendUsers;

    this.dataService.formSend.sendUsers = this.dataService.getAllForms.formListsDeatils.forms[idx].sendUsers;
    this.dataService.formShare.formSlug = this.dataService.getAllForms.formListsDeatils.forms[idx].formSlug;
  }

  /* Show Shared Users */
  showMoreOption(idx, event): void {
    event.stopPropagation();
    this.dataService.getAllForms.formListsDeatils.forms[idx]['showMore'] = !this.dataService.getAllForms.formListsDeatils.forms[idx]['showMore'];
    if (!this.dataService.getAllForms.formListsDeatils.forms[idx]['showMore']) return
   
    this.dataService.sendUsers.option = 'publish';
    this.dataService.formShare.sharedUsers = this.dataService.getAllForms.formListsDeatils.forms[idx].sharedUsers;

    this.dataService.formSend.sendUsers = this.dataService.getAllForms.formListsDeatils.forms[idx].sendUsers;

    this.dataService.formShare.formSlug = this.dataService.getAllForms.formListsDeatils.forms[idx].formSlug;
    // this.dataService.formSend.formSlug = this.dataService.getAllForms.formListsDeatils.forms[idx].formSlug;

    this.dataService.formShare.formTitle = this.dataService.getAllForms.formListsDeatils.forms[idx].formTitle;
  }

  /* Blocking propagation checkbox click */
  selectForm(event): void {
    event.stopPropagation();
  }

  /* sort forms */
  sortForms(sortItem): void {
    this.dataService.getAllForms.sortBy = sortItem
    this.dataService.getAllForms.sortOrder === 'asc' ? this.dataService.getAllForms.sortOrder = 'desc' : this.dataService.getAllForms.sortOrder = 'asc';
    this.formsSandbox.getAllForms();
  }

  ngOnDestroy() {
    this.dataService.resetGetAllForms();
  }

}


