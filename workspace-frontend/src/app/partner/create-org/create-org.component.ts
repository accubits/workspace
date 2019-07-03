import { Component, OnInit } from '@angular/core';
import { PartnerSandbox} from '../partner.sandbox';
import { PartnerDataService} from '../../shared/services/partner-data.service';
import { UtilityService } from '../../shared/services/utility.service';



@Component({
  selector: 'app-create-org',
  templateUrl: './create-org.component.html',
  styleUrls: ['./create-org.component.scss']
})
export class CreateOrgComponent implements OnInit {
  public toggleShow = false;
  public toggleShowLast = false;
  countryNames: '';
  verticalNames: '';

isSubmit = false;
isEmailValidated = false;



  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox: PartnerSandbox,
    public utilityService: UtilityService


  ) { }

  ngOnInit() {
    this.partnerSandbox.getCountry();
    this.partnerSandbox.getVerticals();
  }

   /*  Create Organisations [Start] */

   createOrganisations() {
    this.isSubmit =  true;

    if (!this.validateElement()) {
      return;
    };

    if (!this.validateEmail()) {
      return;
    }
     this.partnerSandbox.createOrganisations();
   }
    /* Create Organisations [End] */

    /* Validate Email*/

    validateEmail() {
      this.isEmailValidated = this.utilityService.validateEmail(this.partnerDataService.createOrganisation.adminEmail);
      return this.isEmailValidated;
    }


/*Validating Element*/
    validateElement(): void {
      (!this.partnerDataService.createOrganisation.name || !this.countryNames || !this.verticalNames || !this.partnerDataService.createOrganisation.adminEmail) ?
        this.partnerDataService.selectedElement.isValidated = false : this.partnerDataService.selectedElement.isValidated = true;
    return this.partnerDataService.selectedElement.isValidated;
    }


   /* Entering the selected countries*/
   countries(country) {
    this.countryNames = country.name;
    this.partnerDataService.createOrganisation.countrySlug = country.slug;
  }

   /* Entering the selected verticals*/
   verticals(vertical) {
    this.verticalNames = vertical.name;
    this.partnerDataService.createOrganisation.verticalSlug = vertical.slug;
  }

  closeCreatePopup() {
    this.partnerDataService.resetOrganisation();
    this.partnerDataService.selectedElement.isValidated = true;
    this.partnerDataService.showCreateOrganisationpopup.show = false;
  }

  contentShow() {
    this.toggleShow = false;
  }

  contentShowLast() {
    this.toggleShowLast = false;
  }


}
