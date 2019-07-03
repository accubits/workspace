import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Observable } from "rxjs/Observable";
import {PartnerManagerDataService} from '../services/partner-manager-data.service';
import { Configs } from '../../config';


@Injectable()
export class PartnerManagerApiService {

  constructor(
    public partnerManagerDataService:PartnerManagerDataService,
    private http: HttpClient,

  ) { }

  /* Getting  all partners[Start] */

  getAllPartners(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'partnermanagement/fetchAllPartners'
  
    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));

  }
  /* Getting  all partners[End] */

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
