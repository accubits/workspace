import { Component, OnInit } from '@angular/core';
import { PartnerSandbox} from '../partner.sandbox'
import { PartnerDataService} from '../../shared/services/partner-data.service'
import { PartnerUtilityService } from '../../shared/services/partner-utility.service'

@Component({
  selector: 'app-org-table-head',
  templateUrl: './org-table-head.component.html',
  styleUrls: ['./org-table-head.component.scss']
})
export class OrgTableHeadComponent implements OnInit {

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,
    private partnerUtilityService : PartnerUtilityService
  ) { } 


  ngOnInit() {
    // this.partnerDataService.OrganisationTabDetails.tab = 'allOrg'
    // this.partnerSandbox.getAllOrganisations();
  }

 


/* Checking all organisations[Start] */ 
 checkAllOrganisations($event):void{
  for(let i=0; i<this.partnerDataService.getOrganisationDetails.organizations.length;i++){
    this.partnerDataService.getOrganisationDetails.organizations[i].selected =  this.partnerDataService.organisationDetails.selectedAll;
     this.partnerUtilityService.manageOrganisationSelection(this.partnerDataService.getOrganisationDetails.organizations[i].selected,i)
  }
}


/* sort forms */
sortOrganisation(sortItem):void{
  this.partnerDataService.OrganisationPageDetails.sortBy = sortItem
  this.partnerDataService.OrganisationPageDetails.sortOrder ==='asc' ? this.partnerDataService.OrganisationPageDetails.sortOrder = 'desc' : this.partnerDataService.OrganisationPageDetails.sortOrder = 'asc'; 
  this.partnerSandbox.getAllOrganisations();
}

ngOnDestroy(){
  this.partnerDataService.resetOrganisationDetails();
}


}
