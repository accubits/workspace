import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import { PartnerSandbox} from '../partner.sandbox'
import { PartnerUtilityService } from '../../shared/services/partner-utility.service'


@Component({
  selector: 'app-expired-table-head',
  templateUrl: './expired-table-head.component.html',
  styleUrls: ['./expired-table-head.component.scss']
})
export class ExpiredTableHeadComponent implements OnInit {

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,
    public partnerUtilityService:PartnerUtilityService

  ) { }

  ngOnInit() {
  }

  sortLicenses(sortItem):void{
    this.partnerDataService.licensePageDetails.sortBy = sortItem
    this.partnerDataService.licensePageDetails.sortOrder ==='asc' ? this.partnerDataService.licensePageDetails.sortOrder = 'desc' : this.partnerDataService.licensePageDetails.sortOrder = 'asc'; 
    this.partnerSandbox.getAllLicenses();
  }

  /* Checking all Licenses[Start] */ 
 checkAllLicenses($event):void{
  for(let i=0; i<this.partnerDataService.getLicenseDetails.license.length;i++){
    this.partnerDataService.getLicenseDetails.license[i].selected =  this.partnerDataService.selectedLicenseDetails.selectedAll;
     this.partnerUtilityService.manageLicenseSelection(this.partnerDataService.getLicenseDetails.license[i].selected,i)
  }
  
}

}
