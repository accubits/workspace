import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { CookieService } from 'ngx-cookie-service';
import { Observable } from "rxjs/Observable";
import { Configs } from '../../config';
import { SettingsDataService } from './settings-data.service';
import { UtilityService } from './utility.service'


@Injectable()
export class SettingsApiService {

  constructor(private cookieService: CookieService,
    public settingsDataService: SettingsDataService,
    private utilityService: UtilityService,

    private http: HttpClient
   )
  {}

   /* Change Password[Start] */

    changePassword(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'usermanagement/change-password'
    let data = {
      "current_password": this.settingsDataService.changePassword.oldPassword,
      "new_password": this.settingsDataService.changePassword.newPassword,
      "new_password_confirmation": this.settingsDataService.changePassword.confirmPwd

    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }

   /* Change Password[End] */

   /* Fetch Profile Details For Edit[Start] */

    fetchProfileDetailsEdit(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'usermanagement/get-profile';
  
    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Fetch Profile  Details For Edit[End] */

/* Edit Profile [Start] */

    editProfile(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'usermanagement/edit-profile'
    var fd = new FormData();
    // fd.append('file', this.settingsDataService.editProfile.file[0]);
    // fd.append('first_name',this.settingsDataService.editProfile.first_name);
    // fd.append('last_name','vjjk');
    // fd.append('birth_date',this.utilityService.convertToUnix(this.settingsDataService.editProfile.birth_date));
    // fd.append('interests',JSON.stringify(this.settingsDataService.editProfile.interests));

    fd.append('file', this.settingsDataService.editProfile.file[0]);
    fd.append('data',JSON.stringify({
          first_name:this.settingsDataService.editProfile.first_name,
          last_name:this.settingsDataService.editSettingsTemplate.lastName,
          birth_date:this.utilityService.convertToUnix(this.settingsDataService.editProfile.birth_date),
          interests:JSON.stringify(this.settingsDataService.editProfile.interests)
    }));
    let header = new HttpHeaders().set('Content-Type', 'application/json');
      let headers = { headers: header };
      // Preparing HTTP Call
      return this.http.post(URL, fd)
        .map(this.checkResponse)
        .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }

/* Edit Profile [End] */

/* Edit Partner Profile [Start] */

editPartnerProfile(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'usermanagement/edit-profile'
  var fd = new FormData();
  fd.append('file', this.settingsDataService.editProfile.file[0]);
  fd.append('data',JSON.stringify({
    first_name:this.settingsDataService.editProfile.first_name,
    last_name:this.settingsDataService.editSettingsTemplate.lastName,
    birth_date:this.settingsDataService.editProfile.birth_date,
  }));
  let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, fd)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

/* Edit Partner Profile [End] */

/* Remove Profile [Start] */

    removeProfile(): Observable<any> {
      // Preparing Post variables
      let URL = Configs.api + 'usermanagement/delete-profileimg';
      
      // Preparing HTTP Call
      return this.http.delete(URL)
        .map(this.checkResponse)
        .catch((error) => Observable.throw(error.error.error || 'Server error.'));

    }
    
/* Remove Profile [End] */

/* Get Licenses[Start] */
getLicense(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/fetchOrgLicense'
  // let data = this.partnerDataService.licenseDetails;
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
  };
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.json().error || 'Server error.'));

}
/* Get Licenses [End] */
 /*  Create Licenses[Start] */


createLicenses(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/createLicenseRequest'
  // let data = this.hrmDataService.createLicense;
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "maxUsers":  this.settingsDataService.createLicense.maxUsers,
    "licenseType": this.settingsDataService.createLicense.licenseType
   
  }
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
    "licenseKey": this.settingsDataService.renewLicense.licenseKey,
    "maxUsers":this.settingsDataService.updateRenewLicense.maxUsers,
  };
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

 /*  Renew Licenses[End] */

 /* Edit Partner Profile [Start] */

editBackgroundSettingsImage(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/orgSettings'
  var fd = new FormData();
  fd.append('file', this.settingsDataService.changeBackgroundSettings.file[0]);
  fd.append('data',JSON.stringify({
    "orgSlug": this.cookieService.get('orgSlug'),
    resetToDefault:this.settingsDataService.changeBackgroundSettings.resetToDefault,
    dashboardMsg: this.settingsDataService.changeBackgroundSettings.dashboardMsg,
    workReport: this.settingsDataService.changeBackgroundSettings.workReport,
    timezone: this.settingsDataService.changeBackgroundSettings.timezone
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
  let URL = Configs.api + 'orgmanagement/fetchOrgSettings'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
   
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
