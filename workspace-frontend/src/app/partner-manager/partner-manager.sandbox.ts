import { Injectable } from '@angular/core';
import {PartnerManagerDataService } from '../shared/services/partner-manager-data.service';
import { PartnerManagerApiService } from '../shared/services/partner-manager-api.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { CookieService } from 'ngx-cookie-service';
import { SettingsDataService } from '../shared/services/settings-data.service';
import { SettingsApiService } from '../shared/services/settings-api.service';
import merge from 'deepmerge';
import { ToastService } from '../shared/services/toast.service';

@Injectable()
export class PartnerManagerSandbox{

  constructor(
    public partnerManagerDataService:PartnerManagerDataService,
    public partnerManagerApiService:PartnerManagerApiService,
    public settingsDataService:SettingsDataService,
    private settingsApiService:SettingsApiService,
    private spinner: Ng4LoadingSpinnerService,
    private cookieService: CookieService,
    private toastService: ToastService,

  ) { }

   /* Sandbox to handle API call for getting All Partners[Start] */

   getAllPartners() {
      this.spinner.show();
    // Accessing partner manager  API service 
    return this.partnerManagerApiService.getAllPartners().subscribe((result: any) => {
      this.partnerManagerDataService.getPartnersDetails = result.data;
     

      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
 /* Sandbox to handle API call getting All Partners[End] */

 /*Sandbox to handle API call for  Fetch Profile Details[Start] */

 fetchProfileDetailsEdit() {
  this.spinner.show();
    // Accessing settings API service

  this.settingsDataService.editSettingsTemplate.interest = [];
  return this.settingsApiService.fetchProfileDetailsEdit().subscribe((result: any) => {
  console.log(result.data.message);

  this.settingsDataService.editSettingsTemplate = merge(this.settingsDataService.editSettingsTemplate,result.data);
  this.settingsDataService.editSettingsTemplate.birthDate =  this.settingsDataService.editSettingsTemplate.birthDate? new Date(this.settingsDataService.editSettingsTemplate.birthDate * 1000):null;
  this.spinner.hide();

},
err => {
  console.log(err);
  this.spinner.hide();

})
}
/* Sandbox to handle API call for  Fetch Profile Details[End] */

  /*Sandbox to handle API call for  Edit partner Profile[Start] */

  editPartnerProfile() {
    this.spinner.show();
     // Accessing settings API service

    return this.settingsApiService.editPartnerProfile().subscribe((result: any) => {
    console.log(result.message);
    this.settingsDataService.resetEditSettings();
    this.fetchProfileDetailsEdit();
    this.toastService.Success('', 'User Profile updated successfully') 
    this.spinner.hide();
  },
    err => {
      console.log(err);
      this.spinner.hide();

    })   
  }

  /*Sandbox to handle API call for  Edit Partner Profile[End] */

 /* Sandbox to handle API call for remove profile [Start] */

  removeProfile(){
    this.spinner.show();
     // Accessing settings API service

     return this.settingsApiService. removeProfile().subscribe((result: any) => {
      console.log(result.message);
      this.toastService.Success('', 'deleted successfully') 
      this.fetchProfileDetailsEdit();
      this.spinner.hide();
     
  },
  err => {
      console.log(err);
      this.spinner.hide();
  })
}
/* Sandbox to handle API call for remove profile [End] */


}

