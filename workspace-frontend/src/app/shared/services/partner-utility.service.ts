import { Injectable } from '@angular/core';
import { PartnerDataService } from './partner-data.service';

@Injectable()
export class PartnerUtilityService {

  constructor(
    public partnerDataService: PartnerDataService
  ) { }


  /* Managing Organisation selction[Start] */
   manageOrganisationSelection(isSelcted:boolean,index: number): void {
    // Checking the Organisation is selected
    if (isSelcted) {
      this.partnerDataService.organisationDetails.selectedOrganisationIds.push(this.partnerDataService.getOrganisationDetails.organizations[index].orgSlug);  // Inserting in to selected organisation list
    //this.partnerDataService.selectedIndex.index = isSelcted;
    //console.log(this.partnerDataService.selectedIndex.index);
    } else {
      let idx = this.partnerDataService.organisationDetails.selectedOrganisationIds.indexOf(this.partnerDataService.getOrganisationDetails.organizations[index].orgSlug);
      this.partnerDataService.organisationDetails.selectedOrganisationIds.splice(idx, 1); // Deleting fron slected task list
    }
    this.partnerDataService.organisationDetails.selectedOrganisationIds.length > 0 ? this.partnerDataService.organisationDetails.showPopup = true : this.partnerDataService.organisationDetails.showPopup = false;

  }
  /* Managing Organisation selction[End] */


   /* Managing License selction[Start] */
   manageLicenseSelection(isSelcted:boolean,index: number): void {
    // Checking the License is selected
    if (isSelcted) {
      this.partnerDataService. selectedLicenseDetails.selectedLicenseIds.push(this.partnerDataService.getLicenseDetails.license[index].slug);  // Inserting in to selected license list
    } else {
      let idx = this.partnerDataService.selectedLicenseDetails.selectedLicenseIds.indexOf(this.partnerDataService.getLicenseDetails.license[index].slug);
      this.partnerDataService.selectedLicenseDetails.selectedLicenseIds.splice(idx, 1); // Deleting fron slected License list
    }
    this.partnerDataService.selectedLicenseDetails.selectedLicenseIds.length > 0 ? this.partnerDataService.selectedLicenseDetails.showPopup = true : this.partnerDataService.selectedLicenseDetails.showPopup = false;

  }
  /* Managing License selection[End] */

}
