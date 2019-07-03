import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import { PartnerSandbox} from '../partner.sandbox'

@Component({
  selector: 'app-license-content-updated',
  templateUrl: './license-content-updated.component.html',
  styleUrls: ['./license-content-updated.component.scss']
})
export class LicenseContentUpdatedComponent implements OnInit {

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,
  ) { }

  ngOnInit() {
    this.partnerSandbox.getLicense();
  }

  renewLicenseRequests():void{
    // this.partnerDataService.renewLicense.licenseKey=this.partnerDataService.selectedOrganisation.licenseKey 
    //  this.partnerSandbox.renewLicenses();

    this.partnerDataService.renewRequestPopup.show = true;  
    this.partnerDataService.updateRenewLicense.name =this.partnerDataService.getOrgLicenseDetails.orgName
    this.partnerDataService.updateRenewLicense.licenseType =this.partnerDataService.getOrgLicenseDetails.type
    this.partnerDataService.updateRenewLicense.maxUsers =this.partnerDataService.getOrgLicenseDetails.users.totalUsers 

   }

   requestLicense():void{ 
    this.partnerDataService.requestLicensePopup.show = true;
    this.partnerDataService.selectedLicenseRequestDetails.name=this.partnerDataService.selectedOrganisation.name
    this.partnerDataService.selectedLicenseRequestDetails.orgSlug=this.partnerDataService.selectedOrganisation.orgSlug
    this.partnerDataService.orgDetailsPop.showPopup = false;

   }

   deleteLicense():void{
    // this.partnerDataService.renewLicense.licenseKey=this.partnerDataService.selectedOrganisation.licenseSlug 

    this.partnerDataService.cancelPop.showPopup = true;
   }
   
}
