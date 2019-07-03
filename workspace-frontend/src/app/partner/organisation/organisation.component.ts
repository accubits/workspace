import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import {PartnerSandbox} from '../partner.sandbox'

@Component({
  selector: 'app-organisation',
  templateUrl: './organisation.component.html',
  styleUrls: ['./organisation.component.scss']
})
export class OrganisationComponent implements OnInit {
  country: any
  vertical: any
  countryName: ''
  selectedOrganisation:any
  
  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox
  ) { }

  ngOnInit() {

    this.partnerSandbox.getCountry();
    this.partnerSandbox.getVerticals();

  }


  /*delete all organisations*/
  deleteOrganisations():void{
    this.partnerDataService.deleteBulkPopup.show = true;
  }

  /* Edit Organisations */
  editOrganisation(Index) {
    this.partnerDataService.showEditOrganisationpopup.show = true // showing edit popup
    this.selectedOrganisation = this.partnerDataService.getOrganisationDetails.organizations.filter(
      organisation => organisation.orgSlug === this.partnerDataService.organisationDetails.selectedOrganisationIds[0]
    ) [0]

 // Assigning selected org detail to update org model
 this.partnerDataService.updateOrganisation.name = this.selectedOrganisation.name;
 this.partnerDataService.updateOrganisation.orgSlug = this.selectedOrganisation.orgSlug;
 this.partnerDataService.updateOrganisation.email = this.selectedOrganisation.adminEmails;
 this.partnerDataService.updateOrganisation.adminUserSlug = this.selectedOrganisation.adminUserSlugs;

 //getting country from selected country list
 this.country = this.partnerDataService.countryTemplate.countries.filter(
 country => country.slug === this.selectedOrganisation.countrySlug)[0]
 this.partnerDataService.updateOrganisation.countryName = this.country.name;
 this.partnerDataService.updateOrganisation.countrySlug = this.country.slug;

 //getting verticals from selected vertical
 this.vertical = this.partnerDataService.getVerticals.verticals.filter(
   vertical => vertical.slug === this.selectedOrganisation.verticalSlug)[0]
 this.partnerDataService.updateOrganisation.verticalName = this.vertical.name;
 this.partnerDataService.updateOrganisation.verticalSlug = this.vertical.slug;

}

}
