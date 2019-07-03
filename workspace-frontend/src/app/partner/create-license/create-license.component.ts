import { Component, OnInit } from '@angular/core';
import { PartnerSandbox } from '../partner.sandbox'
import { PartnerDataService } from '../../shared/services/partner-data.service'

@Component({
  selector: 'app-create-license',
  templateUrl: './create-license.component.html',
  styleUrls: ['./create-license.component.scss']
})
export class CreateLicenseComponent implements OnInit {
  toggleShow: boolean = false;
  orgShow: boolean = false;
  organisationName: ''

  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox: PartnerSandbox,
  ) { }

  ngOnInit() {
    // Inital Executions 
    this.partnerDataService.OrganisationTabDetails.tab = 'allOrg'
   this.partnerDataService.createLicense.licenseType = 'Annual';
   this.partnerSandbox.getAllOrganisations();
  }

  toggleClose(){
    this.toggleShow = false;
    this.orgShow = false;
  }
  selectOrganisation(organisation) {
    this.organisationName = organisation.name;
    this.partnerDataService.createLicense.orgSlug = organisation.orgSlug

  }

  /*  Create licenses  */
  createLicenses() {
    if (!this.validateElement()) {
      return;
    };

    this.partnerSandbox.createLicense();

  }

  /* License Type*/

  typeOfLicense(type) {
    this.partnerDataService.createLicense.licenseType = type;
  }

  validateElement(): void {
    (!this.partnerDataService.createLicense.maxUsers || !this.partnerDataService.createLicense.licenseType || !this.organisationName) ?
      this.partnerDataService.selectedElement.isValidated = false : this.partnerDataService.selectedElement.isValidated = true;
    return this.partnerDataService.selectedElement.isValidated;
  }

  closeCreatePopup() {
    this.partnerDataService.resetLicenses();
    this.partnerDataService.selectedElement.isValidated = true;
    this.partnerDataService.showCreateLicensepopup.show = false;

  }


}
