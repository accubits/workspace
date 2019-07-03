import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import { PartnerSandbox} from '../partner.sandbox'

@Component({
  selector: 'app-renew-license-pop',
  templateUrl: './renew-license-pop.component.html',
  styleUrls: ['./renew-license-pop.component.scss']
})
export class RenewLicensePopComponent implements OnInit {

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,
  ) { }

  ngOnInit() {
    
    // for(let i=0; i<this.partnerDataService.getLicenseDetails.license.length;i++){
    //   this.partnerDataService.getLicenseDetails.license[i].selected = false;
    // }
    // this.partnerDataService.selectedLicenseDetails.selectedLicenseIds = [] 
    this.partnerDataService.selectedLicenseDetails.selectedLicenseIds = [] 
    this.partnerSandbox.getAllLicenses();

  }

  closeOrgDetails(): void {
    this.partnerDataService.renewPopup.showPopup = false;
    this.partnerDataService.renewLicensePopup.show=false;
    //this.partnerDataService.licenseDetailsPop.showPopup = false; 


  }

  renewLicenseRequests():void{
    this.partnerDataService.renewRequestPopup.show = true;  
    this.partnerDataService.updateRenewLicense.name =this.partnerDataService.selectedCurrentLicenses.orgName
    this.partnerDataService.updateRenewLicense.licenseType =this.partnerDataService.selectedCurrentLicenses.licenseType
    this.partnerDataService.updateRenewLicense.maxUsers =this.partnerDataService.selectedCurrentLicenses.maxUsers
    //this.partnerDataService.updateRenewLicense.key =this.partnerDataService.selectedCurrentLicenses.key


  }
}



