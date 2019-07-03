import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import {PartnerSandbox} from '../partner.sandbox'

@Component({
  selector: 'app-delete-pop',
  templateUrl: './delete-pop.component.html',
  styleUrls: ['./delete-pop.component.scss']
})
export class DeletePopComponent implements OnInit {

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox:PartnerSandbox

  ) { }

  ngOnInit() {
  }

  cancelCheck(){
    this.partnerDataService.cancelPop.showPopup = false; 
    this.partnerDataService.requestDetailsPop.showPopup = false;

  }

   deleteRequest(){

       if(this.partnerDataService.OrganisationTabDetails.tab == 'allOrg' ||this.partnerDataService.OrganisationTabDetails.tab == 'unlicensedOrg'){
        this.partnerDataService.selectedRequest.licenseSlug=this.partnerDataService.selectedOrganisation.licenseSlug
         this.partnerSandbox.deleteLicenseRequest();
        }

        // if (this.partnerDataService.OrganisationTabDetails.tab == 'unlicensedOrg'){
        //   this.partnerDataService.selectedRequest.licenseSlug=this.partnerDataService.selectedUnlicensedOrganisation.licenseSlug
        //   this.partnerSandbox.deleteLicenseRequest();
        // }

        if(this.partnerDataService.licenseDetails.tab == 'licenseRequests'){
       this.partnerDataService.selectedRequest.licenseSlug = this.partnerDataService.selectedLicense.licenseRequestSlug
       this.partnerSandbox.deleteLicenseRequest();
        }

     }
    

  }

