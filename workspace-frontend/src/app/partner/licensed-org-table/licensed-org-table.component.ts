import { Component, OnInit } from '@angular/core';
import { PartnerSandbox } from '../partner.sandbox'
import { PartnerDataService } from '../../shared/services/partner-data.service'
import { PartnerUtilityService } from '../../shared/services/partner-utility.service'


@Component({
  selector: 'app-licensed-org-table',
  templateUrl: './licensed-org-table.component.html',
  styleUrls: ['./licensed-org-table.component.scss']
})
export class LicensedOrgTableComponent implements OnInit {

  orgDetails: boolean = false;
  moreOptions: boolean = false;
  showMoreOptions: boolean = false;
  country: any
  moreOption: boolean = false;
  vertical: any
  countryName: ''
  indexValue: number
  
  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox: PartnerSandbox,
    public partnerUtilityService: PartnerUtilityService
  ) { }

  ngOnInit() {
    this.partnerDataService.OrganisationTabDetails.tab = 'licensedOrg'
    this.partnerSandbox.getAllOrganisations();

    
    // this.partnerSandbox.getCountry();
    // this.partnerSandbox.getVerticals();
  }

  showOrgDetails(i): void {
    this.partnerDataService.orgDetailsPop.showPopup = true;
    this.partnerDataService.selectedOrganisation = this.partnerDataService.getOrganisationDetails.organizations[i]
  }

  closeOrgMore(): void {
    this.partnerDataService.moreOption['showMore'] = false
  }

  showMoreOption(event, i) {
    event.stopPropagation();
    this.partnerDataService.getOrganisationDetails.organizations[i]['showMore'] = !this.partnerDataService.getOrganisationDetails.organizations[i]['showMore'];
  }

  selectTask(event): void {
    event.stopPropagation();
  }

  ngOnDestroy() {
    this.partnerDataService.resetOrganisationDetails();
 }

}
