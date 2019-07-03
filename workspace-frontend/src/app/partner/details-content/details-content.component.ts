import { Component, OnInit } from '@angular/core';
import { PartnerSandbox } from '../partner.sandbox'
import { PartnerDataService } from '../../shared/services/partner-data.service'
import { element } from 'protractor';

@Component({
  selector: 'app-details-content',
  templateUrl: './details-content.component.html',
  styleUrls: ['./details-content.component.scss']
})
export class DetailsContentComponent implements OnInit {
  country: any
  vertical: any
  countryName: ''

  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox: PartnerSandbox,
  ) { }

  ngOnInit() {
        // Initial executions
    //this.partnerDataService.OrganisationTabDetails.tab = 'allOrg'
    //this.partnerSandbox.getAllOrganisations();
    this.partnerSandbox.getCountry();
    this.partnerSandbox.getVerticals();
  }

  /* Edit Organisations */
  editOrganisation() {
    this.partnerDataService.showEditOrganisationpopup.show = true // showing edit popup

    // Assigning selected org detail to update org model
    this.partnerDataService.updateOrganisation.name = this.partnerDataService.selectedOrganisation.name;
    this.partnerDataService.updateOrganisation.orgSlug = this.partnerDataService.selectedOrganisation.orgSlug;
    this.partnerDataService.updateOrganisation.email = this.partnerDataService.selectedOrganisation.adminEmails;
    this.partnerDataService.updateOrganisation.adminUserSlug = this.partnerDataService.selectedOrganisation.adminUserSlugs;

    //getting country from selected country list
    this.country = this.partnerDataService.countryTemplate.countries.filter(
    country => country.slug === this.partnerDataService.selectedOrganisation.countrySlug)[0]
    this.partnerDataService.updateOrganisation.countryName = this.country.name;
    this.partnerDataService.updateOrganisation.countrySlug = this.country.slug;

    //getting verticals from selected vertical
    this.vertical = this.partnerDataService.getVerticals.verticals.filter(
      vertical => vertical.slug === this.partnerDataService.selectedOrganisation.verticalSlug)[0]
    this.partnerDataService.updateOrganisation.verticalName = this.vertical.name;
    this.partnerDataService.updateOrganisation.verticalSlug = this.vertical.slug;
  }

/* Delete Organisations */
  deleteOrganisation() {
    this.partnerDataService.cancelPop.showPopup = true; 
    this.partnerDataService.showDelete.showPopup = false; 

    //this.partnerSandbox.deleteOrganisation();

  }

  // showpop():void{
  //   this.partnerDataService.showDelete.showPopup = true; 
  //   this.partnerDataService.cancelPop.showPopup = false; 


  // }
}