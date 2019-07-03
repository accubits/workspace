import { Injectable } from '@angular/core';
import { PartnerDataService } from '../shared/services/partner-data.service';
import { PartnerApiService } from '../shared/services/partner-api.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { CookieService } from 'ngx-cookie-service';
import { ToastService } from '../shared/services/toast.service';




@Injectable()
export class PartnerSandbox {


  constructor(
    private toastService: ToastService,
    public partnerDataService: PartnerDataService,
    public partnerApiService: PartnerApiService,
    private spinner: Ng4LoadingSpinnerService,
    private cookieService: CookieService


  ) { }

  /* Sandbox to handle API call for getting All Organisations[Start] */
  getAllOrganisations() {
     this.spinner.show();
    // Accessing partner API service 

    return this.partnerApiService.getAllOrganisations().subscribe((result: any) => {
      this.partnerDataService.getOrganisationDetails = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call getting All Organisations[End] */


  /*Sandbox to handle API call for Create Organisations[Start] */

  createOrganisations() {
    if(this.partnerDataService.partnerAPICall.inProgress) 
    return;
    this.partnerDataService.partnerAPICall.inProgress =  true;
    this.spinner.show();
    // Accessing partner API service 
    this.partnerDataService.createOrganisation. partnerSlug = this.cookieService.get('partnerSlug');

   console.log('createOrg',this.partnerDataService.createOrganisation. partnerSlug);
    return this.partnerApiService.createOrganisations().subscribe((result: any) => {
      console.log(result.data.msg);
      this.partnerDataService.resetOrganisation();
      this.partnerDataService.OrganisationPageDetails.sortBy = 'createdOn';
      this.getAllOrganisations();
      this.partnerDataService.showCreateOrganisationpopup.show = false;
      this.toastService.Success('', 'Organisation created successfully ') 
      this.partnerDataService.partnerAPICall.inProgress = false;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }

  /*Sandbox to handle API call for  Create Organisations[End] */

  /*Sandbox to handle API call for Update Organisations[Start] */

  updateOrganisations() { 
    
    this.spinner.show();
    // Accessing partner API service 

    this.partnerDataService.updateOrganisation. partnerSlug = this.cookieService.get('partnerSlug');
    return this.partnerApiService.updateOrganisations().subscribe((result: any) => {
      console.log(result.data.msg);
      this.partnerDataService.resetOrganisation();  
      this.partnerDataService.showEditOrganisationpopup.show = false;
      this.partnerDataService.orgDetailsPop.showPopup = false;

      this.partnerDataService.showEditUnlicensepopup.show =false;
      this.partnerDataService.UnlicenseDetailsPop.showPopup = false;


      this.toastService.Success('', 'Organisation updated successfully ') 

      
      this.getAllOrganisations();
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }

  /*Sandbox to handle API call for  Update Organisations[End] */


  /* Sandbox to handle API call for deleting  Organisation[Start] */
  deleteOrganisation() {
    this.spinner.show();
    // Accessing task API service
    return this.partnerApiService.deleteOrganisation().subscribe((result: any) => {
      console.log(result.data.message);
      this.partnerDataService.showEditOrganisationpopup.show = false;
      this.partnerDataService.orgDetailsPop.showPopup = false;
      this.partnerDataService.cancelRequestPop.showPopup = false;
      this.toastService.Success('', 'Organisation deleted successfully ') ;
      this.getAllOrganisations();

    },
      err => {
        console.log(err);
        this.spinner.hide();
      })

  }
  /* Sandbox to handle API call for deleting  Organisation[End] */

  /* Sandbox to handle API call for deleting  Organisation[Start] */
  deleteAllOrganisation() {
    this.spinner.show();
    // Accessing task API service
    return this.partnerApiService.deleteAllOrganisation().subscribe((result: any) => {
      console.log(result.data.message);
      this.partnerDataService.organisationDetails.selectedOrganisationIds = [] 
      this.partnerDataService.deleteBulkPopup.show = false;
      this.toastService.Success('', 'Organisation deleted successfully ') ;
      this.getAllOrganisations();

    },
      err => {
        console.log(err);
        this.spinner.hide();
      })

  }
  /* Sandbox to handle API call for deleting  Organisation[End] */

  /* Sandbox to handle API call for deleting  Organisation[Start] */
  // deleteAllLicenseRequests() {
  //   this.spinner.show();
  //   // Accessing task API service
  //   return this.partnerApiService.deleteAllOrganisation().subscribe((result: any) => {
  //     console.log(result.data.message);
  //     this.partnerDataService.organisationDetails.selectedOrganisationIds = [] 
  //     this.partnerDataService.deleteBulkPopup.show = false;
  //     this.toastService.Success('', 'Organisation deleted successfully ') ;
  //     this.getAllOrganisations();

  //   },
  //     err => {
  //       console.log(err);
  //       this.spinner.hide();
  //     })

  // }
  /* Sandbox to handle API call for deleting  Organisation[End] */

  /* Sandbox to handle API call for getting Role details[Start] */
  getRoleDetails() {
     this.spinner.show();
    // Accessing partner API service 
    return this.partnerApiService.getRoleDetails().subscribe((result: any) => {
      // this.partnerDataService.getRole.partnerSlug = result.data.role.partnerSlug
      console.log('pslug'+result.data.roleDetails.partnerSlug)
      if(result.data.roleDetails.partnerSlug){
        this.cookieService.set('partnerSlug', result.data.roleDetails.partnerSlug);
      }
      this.getAllOrganisations();
      this.getSettings();
     
     // this.getAllLicenses();
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for  getting Role details[End] */

  /* Sandbox to handle API call for get countries [Start] */
  getCountry() {
    this.spinner.show();
    // Accessing partner API service 
    return this.partnerApiService.getAllCountries().subscribe((result: any) => {
      console.log(result.data.message);
      this.partnerDataService.countryTemplate.countries = result.data.countries;
      this.spinner.hide();

    },
      err => {
        console.log(err);
        this.spinner.hide();


      })
  }


  /* Sandbox to handle API call for getting countries [End] */


  /* Sandbox to handle API call for getting Verticals [Start] */
  getVerticals() {
    //this.spinner.show();

    // Accessing partner API service 
    return this.partnerApiService.getVerticals().subscribe((result: any) => {
      console.log(result.data.message);
      this.partnerDataService.getVerticals.verticals = result.data.verticals;
      this.spinner.hide();

    },
      err => {
        console.log(err);
        this.spinner.hide();


      })
  }
  /* Sandbox to handle API call for getting Verticals [End] */


  /*Sandbox to handle API call for Create License[Start] */

  createLicense() {
    if(this.partnerDataService.partnerAPICall.inProgress)
    return; 
    this.partnerDataService.partnerAPICall.inProgress =  true;
    this.spinner.show();
    // Accessing partner API service 

    return this.partnerApiService.createLicenses().subscribe((result: any) => {
      console.log(result.data.msg);
      this.partnerDataService.resetLicenses();
     
      this.partnerDataService.showCreateLicensepopup.show = false;
      this.partnerDataService.requestLicensePopup.show = false;
      this.toastService.Success('', 'License Request created successfully') 
      this.partnerDataService.licensePageDetails.sortBy = "requestedOn";
      this.getAllLicenses();
     this.partnerDataService.partnerAPICall.inProgress = false;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }

  /*Sandbox to handle API call for  Create License[End] */

   /*Sandbox to handle API call for Update Organisations[Start] */

   updateLicenses() { 
    
    this.spinner.show();
    // Accessing partner API service 

    return this.partnerApiService.updateLicenses().subscribe((result: any) => {
      console.log(result.data.msg);
      this.partnerDataService.resetLicenses();  
      this.partnerDataService.showEditLicensepopup.show = false;
      this.partnerDataService.licenseRequestDetailsPop.show = false;
      this.toastService.Success('', 'License Request updated successfully') 

      this.getAllLicenses();
     // this.getAllOrganisations();
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }

  /*Sandbox to handle API call for  Update Organisations[End] */


  /* Sandbox to handle API call for apply Licenses[Start] */
  applyLicenses() {
    this.spinner.show();
   // Accessing partner API service 
   return this.partnerApiService.applyLicenses().subscribe((result: any) => {
    this.partnerDataService.approvePop.showPopup = false;
    // this.toastService.Success('', 'License activated successfully') 
    this.toastService.Success(result.data.msg);

    this.getAllLicenses();
     this.spinner.hide();
   },
     err => {
       console.log(err);
       this.spinner.hide();

     })
 }
 /* Sandbox to handle API call for apply  Licenses[End] */

  /* Sandbox to handle API call for getting All Licenses[Start] */
  getAllLicenses() {
    this.spinner.show();
   // Accessing partner API service 
   return this.partnerApiService.getAllLicenses().subscribe((result: any) => {
     this.partnerDataService.getLicenseDetails = result.data;
     this.spinner.hide();
   },
     err => {
       console.log(err);
       this.spinner.hide();

     })
 }
 /* Sandbox to handle API call getting All Licenses[End] */

   /* Sandbox to handle API call for  Renew Licenses[Start] */


 renewLicenses(){
  
  this.spinner.show();
  // Accessing partner API service 

  return this.partnerApiService.renewLicenseRequests().subscribe((result: any) => {
    console.log(result.data.msg);
    
    this.partnerDataService.orgDetailsPop.showPopup = false;
    this.partnerDataService.UnlicenseDetailsPop.showPopup = false;
    this.partnerDataService.renewPopup.showPopup = false;
    this.partnerDataService.renewLicensePopup.show = false;
    this.partnerDataService.renewRequestPopup.show = false;


    this.toastService.Success('', 'License renewal request is sent') 
    this.getAllLicenses();

    this.spinner.hide();
  },
    err => {
      console.log(err);
      this.spinner.hide();

    })
 }
   /* Sandbox to handle API call for Renew Licenses[End] */

   /* Sandbox to handle API call for  Forward License Requests[Start] */


forwardLicenseRequests(){
  
  this.spinner.show();
  // Accessing partner API service 

  return this.partnerApiService.forwardLicenseRequests().subscribe((result: any) => {
    console.log(result.data.msg);
    this.partnerDataService.licenseRequestDetailsPop.show = false;
    this.getAllLicenses();

    this.toastService.Success('', 'LicenseRequest forwarded successfully ') 
    //this.getAllLicenses();

    this.spinner.hide();
  },
    err => {
      console.log(err);
      this.spinner.hide();

    })
 }
   /* Sandbox to handle API call for Forward License Requests[End] */


 /* Sandbox to handle API call for Delete License Request [Start] */

 deleteLicenseRequest(){
  this.spinner.show();
   // Accessing settings API service

   return this.partnerApiService. deleteLicenseRequest().subscribe((result: any) => {
    console.log(result.data.msg);
    this.partnerDataService.requestDetailsPop.showPopup = false;
    this.partnerDataService.cancelPop.showPopup = false;
    this.partnerDataService.orgDetailsPop.showPopup = false;

    this.partnerDataService.cancelRequestPop.showPopup = false;
    this.toastService.Success('', 'LicenseRequest cancelled') 
    this.getAllLicenses();
    this.spinner.hide();
   
},
err => {
    console.log(err);
    this.spinner.hide();
})
}
/* Sandbox to handle API call for Delete License Request [End] */

 /* Sandbox to handle API call for get Licenses[Start] */
 getLicense() {
  this.spinner.show();
 // Accessing partner API service 
 return this.partnerApiService.getLicense().subscribe((result: any) => {
  this.partnerDataService.getOrgLicenseDetails = result.data;
   this.spinner.hide();
 },
   err => {
     console.log(err);
     this.spinner.hide();

   })
}
/* Sandbox to handle API call get Licenses[End] */

 /*Sandbox to handle API call for  Edit partner Profile[Start] */

 editSettingsImage() {
  this.spinner.show();
   // Accessing settings API service

  return this.partnerApiService.editSettingsImage().subscribe((result: any) => {
  console.log(result.message);
  // this.settingsDataService.resetEditSettings();
  // this.fetchProfileDetailsEdit();
  this.toastService.Success(result.data.msg) ;
  this.spinner.hide();
},
  err => {
    console.log(err);
    this.spinner.hide();

  })   
}

/*Sandbox to handle API call for  Edit Partner Profile[End] */

/*Sandbox to handle API call for  Edit partner Profile[Start] */

editBackgroundSettingsImage() {
  this.spinner.show();
   // Accessing settings API service

  return this.partnerApiService.editBackgroundSettingsImage().subscribe((result: any) => {
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

/*Sandbox to handle API call for  Edit Partner Profile[End] */
/* Sandbox to handle API call for fetch partner settings [Start] */
getSettings() {
  this.spinner.show();
 // Accessing partner API service 
 return this.partnerApiService.getBackgroundSettingsImage().subscribe((result: any) => {
  this.partnerDataService.getBackgroundSettings.dashboardSettings = result.data.dashboardSettings;
  if(!this.partnerDataService.getBackgroundSettings.dashboardSettings.imageUrl)
  {
    this.partnerDataService.getBackgroundSettings.dashboardSettings.imageUrl='assets/images/ibg.jpg';
  }
   this.spinner.hide();
 },
   err => {
     console.log(err);
     this.spinner.hide();

   })
}
/* Sandbox to handle API call for fetch partner settings[End] */

/* Sandbox to handle API call for remove profile [Start] */

}
