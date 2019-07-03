import { Injectable } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { SettingsDataService } from '../shared/services/settings-data.service';
import { SettingsApiService } from '../shared/services/settings-api.service';
import merge from 'deepmerge'
import { ToastService } from '../shared/services/toast.service';
 
@Injectable()
export class SettingsSandbox {

  constructor(
    private toastService: ToastService,
    public settingsDataService:SettingsDataService,
    private spinner:Ng4LoadingSpinnerService,
    private settingsApiService:SettingsApiService
 
  ) { }

  /* Sandbox to handle API call for change password [Start] */
   
  changePassword() {
    this.spinner.show();
    // Accessing settings API service
    return this.settingsApiService.changePassword().subscribe((result: any) => {
      console.log(result.data.message);
      this.settingsDataService.resetPassword()
      this.toastService.Success('', 'Password changed successfully ') 
      this.spinner.hide();
    },
      err => {
        this.toastService.Error('', err.msg) 
        console.log(err);
        this.spinner.hide();

      })
  } 

  /* Sandbox to handle API call for change password[End] */

  /*Sandbox to handle API call for  Fetch Profile Details[Start] */

      fetchProfileDetailsEdit() {
      this.spinner.show();
        // Accessing settings API service

      this.settingsDataService.editSettingsTemplate.interest = [];
      return this.settingsApiService.fetchProfileDetailsEdit().subscribe((result: any) => {
      // console.log(result.data.message);
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

    /*Sandbox to handle API call for  Edit Profile[Start] */

    editProfile() {
      this.spinner.show();
       // Accessing settings API service

      return this.settingsApiService.editProfile().subscribe((result: any) => {
      console.log(result.message);
      this.settingsDataService.resetEditSettings();
      this.fetchProfileDetailsEdit();

      this.toastService.Success('', 'User Profile updated successfully') 
      this.spinner.hide();
    },
      err => {
        this.toastService.Error('', err.msg) 
        console.log(err);
        this.spinner.hide();

      })   
    }

    /*Sandbox to handle API call for  Edit Profile[End] */

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
        this.fetchProfileDetailsEdit();
       this.settingsDataService.deletePopUp.show = false;
        this.toastService.Success('', 'deleted successfully') 
        this.spinner.hide();
       
    },
    err => {
        console.log(err);
        this.spinner.hide();
    })
  }
/* Sandbox to handle API call for remove profile [End] */

   /* Sandbox to handle API call for get Licenses[Start] */
   getLicense() {
    this.spinner.show();
   // Accessing partner API service 
   return this.settingsApiService.getLicense().subscribe((result: any) => {
    this.settingsDataService.getLicenseDetails = result.data;
     this.spinner.hide();
   },
     err => {
       console.log(err);
       this.spinner.hide();

     })
 }
 /* Sandbox to handle API call get Licenses[End] */

   /*Sandbox to handle API call for Create License[Start] */

   createLicense() {
  
    this.spinner.show();
    // Accessing partner API service 

    return this.settingsApiService.createLicenses().subscribe((result: any) => {
      console.log(result.data.msg);    
     this. settingsDataService.renewLicence.show =false;
      this.toastService.Success(result.data.msg) 
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }


  /*Sandbox to handle API call for  Create License[End] */

   /* Sandbox to handle API call for  Renew Licenses[Start] */


 renewLicenses(){
  
  this.spinner.show();
  // Accessing partner API service 

  return this.settingsApiService.renewLicenseRequests().subscribe((result: any) => {
    console.log(result.data.msg);
    
   
    this.settingsDataService.renewRequestPopup.show =false;


    this.toastService.Success('', 'License renewal request is sent') 

    this.spinner.hide();
  },
    err => {
      console.log(err);
      this.spinner.hide();

    })
 }
   /* Sandbox to handle API call for Renew Licenses[End] */

   /*Sandbox to handle API call for  Edit orgadmin Profile[Start] */

editBackgroundSettingsImage() {
  this.spinner.show();
   // Accessing settings API service

  return this.settingsApiService.editBackgroundSettingsImage().subscribe((result: any) => {
  console.log(result.message);
  // this.settingsDataService.resetEditSettings();
  // this.fetchProfileDetailsEdit();
  this.getSettings();
  this.toastService.Success(result.data.msg) ;
  this.spinner.hide();
},
  err => {
    console.log(err);
    this.spinner.hide();

  })   
}

/*Sandbox to handle API call for  Edit orgadmin Profile[End] */

/* Sandbox to handle API call for fetch orgadmin settings [Start] */
getSettings() {
  this.spinner.show();
 // Accessing partner API service 
 return this.settingsApiService.getBackgroundSettingsImage().subscribe((result: any) => {
  this.settingsDataService.getBackgroundSettings.dashboardSettings = result.data.dashboardSettings;
  console.log('xskjakcc44', this.settingsDataService.getBackgroundSettings.dashboardSettings);
  if(!this.settingsDataService.getBackgroundSettings.dashboardSettings.imageUrl)
  {
    this.settingsDataService.getBackgroundSettings.dashboardSettings.imageUrl='assets/images/ibg.jpg';
  }
   this.spinner.hide();
 },
   err => {
     console.log(err);
     this.spinner.hide();

   })
}
/* Sandbox to handle API call for fetch orgadmin settings[End] */

}
