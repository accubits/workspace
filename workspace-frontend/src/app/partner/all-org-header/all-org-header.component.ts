import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service';
import { PartnerSandbox } from '../partner.sandbox'

@Component({
  selector: 'app-all-org-header',
  templateUrl: './all-org-header.component.html',
  styleUrls: ['./all-org-header.component.scss']
})
export class AllOrgHeaderComponent implements OnInit {
  public licenseMenu = 1;
 

  constructor(
    public partnerDataService : PartnerDataService ,
    public partnerSandbox: PartnerSandbox,
  ) { }

  ngOnInit() {
  }
  pageChanged($event):void{
    this.partnerDataService.organisationDetails.selectedOrganisationIds = [] 
    this.partnerDataService.organisationDetails.selectedAll = false
    this.partnerDataService.OrganisationPageDetails.page = $event;
    //this.partnerDataService.OrganisationTabDetails.tab = 'allOrg'
    this.partnerSandbox.getAllOrganisations();
  }
}
