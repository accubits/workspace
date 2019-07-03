import { Component, OnInit } from '@angular/core';
import { PartnerSandbox} from '../partner.sandbox'
import { PartnerDataService} from '../../shared/services/partner-data.service'

@Component({
  selector: 'app-edit-org',
  templateUrl: './edit-org.component.html',
  styleUrls: ['./edit-org.component.scss']
})
export class EditOrgComponent implements OnInit {
  toggleShow:boolean = false;
  toggleShowLast:boolean = false;
  countryName: '';
  verticalNames:'';

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,
  ) { }

  ngOnInit() {
       // Initial executions
    this.partnerSandbox.getCountry();
    this.partnerSandbox.getVerticals();
  }

   /* Edit Organisations */
   editOrganisations() {
     if(!this.validateElement()){
       return;
     }
    this.partnerSandbox.updateOrganisations();
    this.partnerDataService.organisationDetails.selectedOrganisationIds = [] 

  }

  validateElement(): void {
    (!this.partnerDataService.updateOrganisation.name ) ?
      this.partnerDataService.selectedElement.isValidated = false : this.partnerDataService.selectedElement.isValidated = true;
  return this.partnerDataService.selectedElement.isValidated;
  }
   /* Entering the selected countries*/
   countries(country) {
    this.partnerDataService.updateOrganisation.countryName = country.name;
    this.partnerDataService.updateOrganisation.countrySlug = country.slug
  }

   /* Entering the selected verticals*/
   verticals(vertical) {
    this.partnerDataService.updateOrganisation.verticalName = vertical.name;
    this.partnerDataService.updateOrganisation.verticalSlug = vertical.slug
  }

  hideOverlay(){
    this.toggleShow = false;
  }

  hideLastOverlay(){
    this.toggleShowLast = false;
  }
  
  showSelect(){
    this.toggleShow =! this.toggleShow;
  }

  showSelectLast(){
    this.toggleShowLast =! this.toggleShowLast;
  }

}
