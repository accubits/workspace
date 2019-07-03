import { Component, OnInit, Input } from '@angular/core';
import { PartnerSandbox } from '../partner.sandbox'
import { PartnerDataService } from '../../shared/services/partner-data.service'

@Component({
  selector: 'app-more-option',
  templateUrl: './more-option.component.html',
  styleUrls: ['./more-option.component.scss']
})
export class MoreOptionComponent implements OnInit {

  country: any
  vertical: any
  countryName: ''
  @Input() Index: number

  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox: PartnerSandbox
  ) { }


  ngOnInit() {

    this.partnerDataService.organisationDetails.selectedOrganisationIds = [] 
     this.partnerSandbox.getCountry();
     this.partnerSandbox.getVerticals();
  }

  /*  Renew Licenses */

  renewLicense(event, Index): void {
    event.stopPropagation();
    this.partnerDataService.renewLicensePopup.show = true;
    this.partnerDataService.selectedOrganisationDetails = this.partnerDataService.getOrganisationDetails.organizations[Index]
    // this.partnerDataService.getOrganisationDetails.organizations[Index]['showMore'] =false;
  }


  /*  Request Licenses */

  requestLicense(event, Index): void {
    // event.stopPropagation();
    this.partnerDataService.requestLicensePopup.show = true;
    this.partnerDataService.selectedLicenseRequestDetails = this.partnerDataService.getOrganisationDetails.organizations[Index]
    //this.partnerDataService.getOrganisationDetails.organizations[Index]['showMore'] =false;
  }

  /*  edit Organisation */

  editOrganisation(event, Index): void {
    event.stopPropagation();

    this.partnerDataService.showEditOrganisationpopup.show = true // showing edit popup

    // Assigning selected org detail to update org model
    this.partnerDataService.updateOrganisation.name = this.partnerDataService.getOrganisationDetails.organizations[Index].name;
    this.partnerDataService.updateOrganisation.orgSlug = this.partnerDataService.getOrganisationDetails.organizations[Index].orgSlug;
    this.partnerDataService.updateOrganisation.email = this.partnerDataService.getOrganisationDetails.organizations[Index].adminEmails;
    this.partnerDataService.updateOrganisation.adminUserSlug = this.partnerDataService.getOrganisationDetails.organizations[Index].adminUserSlugs;

    //getting country from selected country list
    this.country = this.partnerDataService.countryTemplate.countries.filter(
      country => country.slug === this.partnerDataService.getOrganisationDetails.organizations[Index].countrySlug)[0]
    this.partnerDataService.updateOrganisation.countryName = this.country.name;
    this.partnerDataService.updateOrganisation.countrySlug = this.country.slug;

    //getting verticals from selected vertical
    this.vertical = this.partnerDataService.getVerticals.verticals.filter(
      vertical => vertical.slug === this.partnerDataService.getOrganisationDetails.organizations[Index].verticalSlug)[0]
    this.partnerDataService.updateOrganisation.verticalName = this.vertical.name;
    this.partnerDataService.updateOrganisation.verticalSlug = this.vertical.slug;
    this.partnerDataService.getOrganisationDetails.organizations[Index]['showMore'] = false;

  }

  deleteOrganisation(event,Index):void{
    this.partnerDataService.selectedOrganisation.orgSlug = this.partnerDataService.getOrganisationDetails.organizations[Index].orgSlug;
    this.partnerDataService.cancelRequestPop.showPopup = true;
  }

}
