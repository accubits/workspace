import { Component, OnInit } from '@angular/core';
import { PartnerSandbox } from '../partner.sandbox'
import { PartnerDataService } from '../../shared/services/partner-data.service'

@Component({
  selector: 'app-create-org-license',
  templateUrl: './create-org-license.component.html',
  styleUrls: ['./create-org-license.component.scss']
})
export class CreateOrgLicenseComponent implements OnInit {
  toggleShow: boolean = false;
  orgShow: boolean = false;

  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox: PartnerSandbox
  ) { }

  ngOnInit() {
   // this.partnerSandbox. getAllOrganisations() ;
   this.partnerDataService.createLicense.licenseType ='Annual'
   //this.partnerDataService.createLicense.licenseType =this.partnerDataService.selectedOrganisation.licenseType

  }
  toggleClose(){
    this.toggleShow= false;
  }

  /*  Create licenses  */
  createLicenses() {
    if (!this.validateElement()) {
      return;
    };

    this.partnerDataService.createLicense.orgSlug=this.partnerDataService.selectedLicenseRequestDetails.orgSlug
    this.partnerSandbox.createLicense();

  }

  /* License Type*/

  typeOfLicense(type) {
    this.partnerDataService.createLicense.licenseType = type;
  }

  validateElement(): void {
    (!this.partnerDataService.createLicense.maxUsers) ?
      this.partnerDataService.selectedElement.isValidated = false : this.partnerDataService.selectedElement.isValidated = true;
    return this.partnerDataService.selectedElement.isValidated;
  }
  

  closeCreatePopup() {
    // this.partnerDataService.resetLicenses();
    this.partnerDataService.requestLicensePopup.show = false;

  }
}
