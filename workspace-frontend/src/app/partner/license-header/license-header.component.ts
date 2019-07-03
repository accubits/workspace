import { Component, OnInit } from '@angular/core';
import { PartnerSandbox} from '../partner.sandbox'
import { PartnerDataService} from '../../shared/services/partner-data.service'
import {Router} from "@angular/router";



@Component({
  selector: 'app-license-header',
  templateUrl: './license-header.component.html',
  styleUrls: ['./license-header.component.scss']
})
export class LicenseHeaderComponent implements OnInit {
  public licenseMenu = 1;
  public licenseTab: string = '';

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,
    private router: Router

  ) { }

  ngOnInit() {
    this.router.events.subscribe(() => { 
      this.licenseTab = this.router.url
      // console.log(this.router.url);
  })
  }

  pageChanged($event):void{
    this.partnerDataService.selectedLicenseDetails.selectedLicenseIds = [] 
    this.partnerDataService.selectedLicenseDetails.selectedAll = false
    this.partnerDataService.licensePageDetails.page = $event;
    //this.partnerDataService.OrganisationTabDetails.tab = 'allOrg'
    this.partnerSandbox.getAllLicenses();
  }

}
