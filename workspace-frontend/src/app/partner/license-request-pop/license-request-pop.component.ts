import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service';
import { PartnerSandbox} from '../partner.sandbox'


@Component({
  selector: 'app-license-request-pop',
  templateUrl: './license-request-pop.component.html',
  styleUrls: ['./license-request-pop.component.scss']
})
export class LicenseRequestPopComponent implements OnInit {

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,


  ) { }

  ngOnInit() {

      // this.partnerDataService.licenseDetails.tab = 'adminLicenseRequests'
      //  this.partnerSandbox.getAllLicenses();
    this.partnerDataService.selectedLicenseDetails.selectedLicenseIds = [] 
    this.partnerSandbox.getAllLicenses();
  }
  closeRequestDetails(): void {
    this.partnerDataService.licenseRequestDetailsPop.show = false;
  }


  /* Forward License Requests*/
  forwardRequests():void{
    //this.partnerDataService.forwardLicense.licenseRequestSlug = this.partnerDataService.selectedAdminRequests.licenseRequestSlug
    this.partnerSandbox.forwardLicenseRequests();
  }

  /* Edit License Requests*/

  editLicense():void{
    this.partnerDataService.showEditLicensepopup.show = true // showing edit popup

    this.partnerDataService.updateLicense.name = this.partnerDataService.selectedAdminRequests.orgName;
    this.partnerDataService.updateLicense.orgSlug = this.partnerDataService.selectedAdminRequests.orgSlug;
    this.partnerDataService.updateLicense.maxUsers = this.partnerDataService.selectedAdminRequests.maxUsers;
    this.partnerDataService.updateLicense.licenseType = this.partnerDataService.selectedAdminRequests.licenseType;
    this.partnerDataService.updateLicense.licenseRequestSlug = this.partnerDataService.selectedAdminRequests.licenseRequestSlug;


  }

}
