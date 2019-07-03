import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import { PartnerSandbox} from '../partner.sandbox'

@Component({
  selector: 'app-license-content',
  templateUrl: './license-content.component.html',
  styleUrls: ['./license-content.component.scss']
})
export class LicenseContentComponent implements OnInit {

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,
  ) { }

  ngOnInit() {
   
  }

  renewLicenseRequests():void{
    this.partnerDataService.renewLicense.licenseKey=this.partnerDataService.selectedOrganisation.licenseKey 
     this.partnerSandbox.renewLicenses();

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
