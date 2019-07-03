import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Observable } from "rxjs/Observable";
import { CookieService } from 'ngx-cookie-service';
import { Routes, RouterModule, Router } from '@angular/router';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';
import { Configs } from '../../config';
import { DataService } from './data.service';
import { HrmDataService } from './hrm-data.service'


@Injectable()
export class FormsApiService {

  constructor(
    private cookieService: CookieService,
    private http: HttpClient,
    public dataService: DataService,
    public hrmDataService: HrmDataService,
  ) { }

  /* Creating new form[Start] */
  createNewForm(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'formmanagement/create'
    let data = this.dataService.createForm;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
  /* Creating new form[End] */

  /* Creating new form[Start] */
  createNewFormPartial(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'formmanagement/create'
    let data = this.dataService.createFormPartial;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
  /* Creating new form[End] */

  /* Getting  all forms[Start] */
  getAllForms(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'formmanagement/fetchAllForms'
    let data = {
      "tab": this.dataService.getAllForms.tab,
      "q": this.dataService.getAllForms.searchText,
      "sortBy": this.dataService.getAllForms.sortBy,
      "sortOrder": this.dataService.getAllForms.sortOrder,
      "page": this.dataService.getAllForms.page,
      "perPage": this.dataService.getAllForms.perPage
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Getting  all forms[End] */

  /* View a single form[Start] */
  viewForm(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'formmanagement/fetch/' + this.dataService.viewForm.selctedFormSlug;

    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* View a single form[End] */

  /* View a published form[Start] */
  viewPublishedForm(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'formmanagement/fetchClientForm/' + this.dataService.viewForm.selctedFormSlug;

    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* View a pubblished form[End] */

  /* submit form response[Start] */
  submitFormResponse(): Observable<any> {
    // Preparing Post variables
    //alert(this.dataService.viewForm.formResponse.trainingRequestSlug);
    let URL = Configs.api + 'formmanagement/clientFormSubmission/' + this.dataService.viewForm.selctedFormSlug;
    var fd = new FormData();
    fd.append('formResponse', JSON.stringify(this.dataService.viewForm.formResponse));


    // Preparing HTTP Call
    return this.http.post(URL, fd)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* submit form response[End] */

  /* sharing forms[Start] */
  shareForm(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'formmanagement/share'
    let data =  {
       'formSlug': this.dataService.formShare.formSlug,
       'sharedUsers': this.dataService.sharedUsers.sharedUserList,
    };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* sharing forms[End] */

  /* send form[Start] */
  sendForm(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'formmanagement/sendForm'
    // let data = this.dataService.formSend;
    let data =  {
      'formSlug': this.dataService.formSend.formSlug,
      'sendUsers': this.dataService.sendUsers.sendUserList,
      'orgSlug': this.cookieService.get('orgSlug'),
      'formStatus': this.dataService.formSend.formStatus,
      'isPublished':this.dataService.formSend.isPublished

   };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* send form[End] */

  /* Getting  all forms[Start] */
  getAllFormRespose(): Observable<any> {

    let URL = Configs.api + 'formmanagement/fetchAllClientFormResponses/' + this.dataService.viewForm.selctedFormSlug;

    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Getting  all forms[End] */

  /* Getting  all forms[Start] */
  getSingleFormRespponseDetails(): Observable<any> {

    let URL = Configs.api + 'formmanagement/fetchNonPaginatedClientFormResponse/' + this.dataService.formResponseManagement.selectedAnswerSlug;

    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Getting  all forms[End] */

  /* Getting  all forms[Start] */
  viewPublishedFormWithAns(): Observable<any> {

    let URL = Configs.api + 'formmanagement/fetchClientFormResponse/' + this.dataService.viewForm.selctedAnsSlug;

    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Getting  all forms[End] */

  /* Delete forms[Start] */
  deleteForms(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'formmanagement/deleteForms'
    let data = {
      "formSlugs": this.dataService.formsSelectionManagement.selectedFormSlugs,
    };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Delete  forms[End] */
 
  /* Delete forms[Start] */
  updateFormStatus(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'formmanagement/updateFormStatus'
    let data = {
      "formStatus": this.dataService.viewForm.updatedStatus,
      "formSlug": this.dataService.viewForm.selctedFormSlug,
    };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Delete  forms[End] */

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
