import { Injectable } from '@angular/core';
import { Observable } from "rxjs/Observable";
import { Configs } from '../../config';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { SuperAdminDataService } from './super-admin-data.service';
 
@Injectable()
export class SuperAdminApiService {
  constructor(private http: HttpClient,
    public superAdminDataService: SuperAdminDataService) { }

   /* API call for get drive files[Start] */
   getCountryList(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'common/country';
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.get(URL,  headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for get drive files[end] */

   /* API call for get drive files[Start] */
   setCountry(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'common/setCountry';
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    let data = {
      "action": this.superAdminDataService.countryDetails.action,
      "slug": this.superAdminDataService.countryDetails.slug,
      "name": this.superAdminDataService.countryDetails.name,
      "isActive": this.superAdminDataService.countryDetails.isActive  
    }
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for get drive files[end] */

    /* API call for get drive files[Start] */
    getVerticalList(): Observable<any> {
      // Preparing Post variables
      let URL = Configs.api + 'orgmanagement/fetchAllVerticals';
      let header = new HttpHeaders().set('Content-Type', 'application/json');
      let headers = { headers: header };
      // Preparing HTTP Call
      return this.http.get(URL,  headers)
        .map(this.checkResponse)
        .catch((error) => Observable.throw(error.json().error || 'Server error.'));
    }
    /* API call for get drive files[end] */

     /* API call for get drive files[Start] */
    setVertical(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'orgmanagement/setVertical';
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    let data = {
      "action": this.superAdminDataService.verticalDetails.action,
      "slug": this.superAdminDataService.verticalDetails.slug,
      "name": this.superAdminDataService.verticalDetails.name,
      "description": this.superAdminDataService.verticalDetails.description,
      "isActive": this.superAdminDataService.verticalDetails.isActive  
    }
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for get drive files[end] */

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
