import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Observable } from "rxjs/Observable";
import { CookieService } from 'ngx-cookie-service';
import {PartnerDataService } from '../services/partner-data.service';
import { Configs } from '../../config';


@Injectable()
export class PartnerApiService {

  constructor(
    private cookieService: CookieService,
    private http: HttpClient,
    public partnerDataService: PartnerDataService,

  ) { }

  /* Getting  all organisations[Start] */

  getAllOrganisations() {
    // Preparing Post variables
    let URL = Configs.api + 'orgmanagement/fetchAllOrgs'
    let data = {
      "partnerSlug":this.cookieService.get('partnerSlug'),
      "tab":this.partnerDataService.OrganisationTabDetails.tab,
      "page":this.partnerDataService.OrganisationPageDetails.page,
      "perPage":this.partnerDataService.OrganisationPageDetails.perPage,
      "sortBy": this.partnerDataService.OrganisationPageDetails.sortBy,
      "sortOrder": this.partnerDataService.OrganisationPageDetails.sortOrder,
    };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));

  }
  /* Getting  all organisations[End] */


/* Create Organisations[Start] */

createOrganisations(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/createOrganization'
  let data = this.partnerDataService.createOrganisation;
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

 /*  Create Organisations[End] */

 /* Update Organisations[Start] */

 updateOrganisations(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/updateOrganization'
  let data = this.partnerDataService.updateOrganisation;
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

 /*  Update Organisations[End] */

 /* Delete Organisations[Start] */
 deleteOrganisation(): Observable<any> {
  let URL = Configs.api + 'orgmanagement/deleteOrganization/' + this.partnerDataService.selectedOrganisation.orgSlug;
  let data = 
  {
    
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  return this.http.post(URL,data,headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* Delete Organisations[End] */

/* Delete Organisations[Start] */
deleteAllOrganisation(): Observable<any> {
  let URL = Configs.api + 'orgmanagement/bulkDeleteOrganization';
  let data = 
  {
    "orgSlugs": this.partnerDataService.organisationDetails.selectedOrganisationIds

  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  return this.http.post(URL,data,headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* Delete Organisations[End] */

/* Delete Organisations[Start] */
// deleteAllLicenses(): Observable<any> {
//   let URL = Configs.api + 'orgmanagement/bulkDeleteLicenseRequest';
//   let data = 
//   {
//     "licenseRequestSlugs": this.partnerDataService.selectedLicenseDetails.selectedLicenseIds

//   }
//   let header = new HttpHeaders().set('Content-Type', 'application/json');
//   let headers = { headers: header };
//   return this.http.post(URL,data,headers)
//     .map(this.checkResponse)
//     .catch((error) => Observable.throw(error.error.error || 'Server error.'));
// }
/* Delete Organisations[End] */



  /* Getting  roles[Start] */
  getRoleDetails(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'common/getRoleDetails'
    let data = {}
     let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL,data,headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));

  }
  /* Getting roles[End] */
  
  /* Get Countries[Start] */

  getAllCountries(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'common/country';
  
    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Get Countries[End] */


  /* Get Verticals[Start] */

  getVerticals(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'orgmanagement/fetchAllVerticals';
  
    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Get Verticals[End] */


/* Create Licenses[Start] */

createLicenses(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/createLicenseRequest'
  let data = this.partnerDataService.createLicense;
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

 /*  Create Licenses[End] */

 /* Renew Licenses[Start] */

renewLicenseRequests(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/renewLicense'
  //let data = this.partnerDataService.renewLicense.licenseKey;
  let data = {
    "licenseKey": this.partnerDataService.renewLicense.licenseKey,
    "maxUsers":this.partnerDataService.updateRenewLicense.maxUsers,
  };
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

 /*  Renew Licenses[End] */


 /* Update Organisations[Start] */

 updateLicenses(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/updateLicenseRequest'
  let data = this.partnerDataService.updateLicense;
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

 /*  Update Organisations[End] */

 /* Forward License Requests[Start] */

forwardLicenseRequests(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/forwardLicenseRequest'
  let data = {
    "licenseRequestSlug": this.partnerDataService.selectedAdminRequests.licenseRequestSlug,
  };
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

 /* Forward License Requests[End] */

/* Forward License Requests[Start] */

applyLicenses(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/activateLicense'
  let data = {
    "orgSlug" :this.partnerDataService.selectedCurrentLicenses.orgSlug,
    "licenseKey": this.partnerDataService.selectedCurrentLicenses.key,
  };
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

 /* Forward License Requests[End] */

 /* Getting  all Licenses[Start] */
 getAllLicenses(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/fetchAllPartnerLicense'
  // let data = this.partnerDataService.licenseDetails;
  let data = {
    "partnerSlug": this.cookieService.get('partnerSlug'),
    "tab":this.partnerDataService.licenseDetails.tab,
    "page":this.partnerDataService.licensePageDetails.page,
    "perPage":this.partnerDataService.licensePageDetails.perPage,
    "sortBy": this.partnerDataService.licensePageDetails.sortBy,
    "sortOrder": this.partnerDataService.licensePageDetails.sortOrder,
  };
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.json().error || 'Server error.'));

}
/* Getting  all Licenses[End] */

/* Delete License Request [Start] */

deleteLicenseRequest(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/cancelLicenseRequest';
  // let data = this.partnerDataService.selectedRequest.licenseSlug;
  let data = {
    "licenseRequestSlug": this.partnerDataService.selectedRequest.licenseSlug,
  };
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };

  
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));

}

/*  Delete License Request [End] */

/* Get Licenses[Start] */
getLicense(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/fetchOrgLicense'
  // let data = this.partnerDataService.licenseDetails;
  let data = {
   // "orgSlug": this.cookieService.get('orgSlug'),
    "orgSlug":this.partnerDataService.selectedOrganisation.orgSlug,

  };
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.json().error || 'Server error.'));

}
/* Get Licenses [End] */

/* Edit Partner Profile [Start] */

editSettingsImage(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/orgSettings'
  var fd = new FormData();
  fd.append('file', this.partnerDataService.changeImage.file[0]);
  fd.append('data',JSON.stringify({
    orgSlug:this.partnerDataService. selectedOrganisation.orgSlug,
    resetToDefault:this.partnerDataService.changeImage.resetToDefault,
    dashboardMsg: this.partnerDataService.changeImage.dashboardMsg,
    workReport: this.partnerDataService.changeImage.workReport,
    timezone: this.partnerDataService.changeImage.timezone
  }));
  let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, fd, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

/* Edit Partner Profile [End] */
/* Edit Partner Profile [Start] */

editBackgroundSettingsImage(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/partnerDashboardSettings';
  var fd = new FormData();
  fd.append('file', this.partnerDataService.changeBackgroundSettings.file[0]);
  fd.append('data',JSON.stringify({
    "partnerSlug": this.cookieService.get('partnerSlug'),
    resetToDefault:this.partnerDataService.changeBackgroundSettings.resetToDefault,
    dashboardMsg: this.partnerDataService.changeBackgroundSettings.dashboardMsg,
    workReport: this.partnerDataService.changeBackgroundSettings.workReport,
    timeZone: this.partnerDataService.changeBackgroundSettings.timeZone
  }));
  let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, fd, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

/* Edit Partner Profile [End] */


/* Getting  all Licenses[Start] */
getBackgroundSettingsImage(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/fetchPartnerDashboardSettings'
  // let data = this.partnerDataService.licenseDetails;
  let data = {
    "partnerSlug": this.cookieService.get('partnerSlug'),
   
  };
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.json().error || 'Server error.'));

}
/* Getting  all Licenses[End] */
  /* Generic function to check Responses[Start] */
   checkResponse(response: any) {
    let results = response
    if (results.status) {
      return results;
    }
    else {
      console.log("Error in API");
      return results;
    }
  }
  /* Generic function to check Responses[End] */
}
