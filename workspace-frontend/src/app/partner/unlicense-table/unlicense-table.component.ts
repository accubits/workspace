import { Component, OnInit } from '@angular/core';
import { PartnerSandbox } from '../partner.sandbox'

import { PartnerDataService } from '../../shared/services/partner-data.service'
import { PartnerUtilityService } from '../../shared/services/partner-utility.service'
@Component({
  selector: 'app-unlicense-table',
  templateUrl: './unlicense-table.component.html',
  styleUrls: ['./unlicense-table.component.scss']
})
export class UnlicenseTableComponent implements OnInit {


  orgDetails: boolean = false;
  moreOptions: boolean = false;
  showMoreOptions:boolean =false;
  country: any
  moreOption: boolean = false;
  vertical: any
  countryName: ''
  indexValue: number

  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox: PartnerSandbox,
    public partnerUtilityService:PartnerUtilityService

  ) { }

  ngOnInit() {
    this.partnerDataService.OrganisationTabDetails.tab = 'unlicensedOrg'
    this.partnerSandbox.getAllOrganisations();
    // this.partnerSandbox.getCountry();
    // this.partnerSandbox.getVerticals();
    this.partnerDataService.resetOrganisationDetails();
  }

  showOrgDetails(i): void {
    this.partnerDataService.orgDetailsPop.showPopup = true;
    this.partnerDataService.selectedOrganisation =this.partnerDataService.getOrganisationDetails.organizations[i]
  }

  closeOrgMore(): void {
    this.partnerDataService.moreOption['showMore']  =false
    
  }

  showMoreOption(event, i) {
    event.stopPropagation();
    //this.moreOptions = true; 
   this.partnerDataService.getOrganisationDetails.organizations[i]['showMore'] =  !this.partnerDataService.getOrganisationDetails.organizations[i]['showMore'];

  } 

  selectTask(event):void{
    event.stopPropagation();
  }

  ngOnDestroy(){
    this.partnerDataService.resetOrganisationDetails();
  }

}