import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import {PartnerSandbox} from '../partner.sandbox'

@Component({
  selector: 'app-request-pop',
  templateUrl: './request-pop.component.html',
  styleUrls: ['./request-pop.component.scss']
})
export class RequestPopComponent implements OnInit {


  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox:PartnerSandbox
  ) { }

  ngOnInit() {
    this.partnerDataService.selectedLicenseDetails.selectedLicenseIds = [] 
   
    this.partnerSandbox.getAllLicenses();

  }

  closeRequestDetails(): void {
    this.partnerDataService.cancelPop.showPopup = false; 
    this.partnerDataService.requestDetailsPop.showPopup = false;
  }

  // deleteRequest(){
  //   this.partnerDataService.selectedRequest.licenseSlug = this.partnerDataService.selectedLicense.licenseRequestSlug
  //   this.partnerSandbox.deleteLicenseRequest();
  // }
  cancelCheck(){
    this.partnerDataService.cancelPop.showPopup = true;
  }
}
