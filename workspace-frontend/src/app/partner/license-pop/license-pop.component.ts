import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import { PartnerSandbox} from '../partner.sandbox'


@Component({
  selector: 'app-license-pop',
  templateUrl: './license-pop.component.html',
  styleUrls: ['./license-pop.component.scss']
})
export class LicensePopComponent implements OnInit {

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,

  ) { }

  ngOnInit() {
    // this.partnerDataService.licenseDetails.tab = 'currentLicenses'
    // this.partnerSandbox.getAllLicenses();

    this.partnerSandbox. getAllOrganisations() ;

  }
  closeOrgDetails(): void {
    this.partnerDataService.licenseDetailsPop.showPopup = false;
    this.partnerDataService.renewLicensePopup.show = false;

  }

  renewLicenseRequests():void{
   this.partnerDataService.renewLicense.licenseKey=this.partnerDataService.selectedOrganisationDetails.licenseKey
    this.partnerSandbox.renewLicenses();
  }
}
