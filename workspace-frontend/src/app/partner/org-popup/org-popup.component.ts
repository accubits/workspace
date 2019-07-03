import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service';

@Component({
  selector: 'app-org-popup',
  templateUrl: './org-popup.component.html',
  styleUrls: ['./org-popup.component.scss']
})
export class OrgPopupComponent implements OnInit {
  //orgDetails:boolean = false;
  toggleDetails: string = 'detailShow';
  constructor(
    public partnerDataService: PartnerDataService
  ) { }

  ngOnInit() {
    
    for(let i=0; i<this.partnerDataService.getOrganisationDetails.organizations.length;i++){
      this.partnerDataService.getOrganisationDetails.organizations[i].selected =  false;
    }
    this.partnerDataService.organisationDetails.selectedOrganisationIds = [] 


  }

  closeOrgDetails(): void {
    this.partnerDataService.orgDetailsPop.showPopup = false;
    this.partnerDataService.cancelPop.showPopup = false;
    this.partnerDataService.showDelete.showPopup = true;


  }
}
