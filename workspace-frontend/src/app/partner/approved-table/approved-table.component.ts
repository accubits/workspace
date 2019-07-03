import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import { PartnerSandbox } from '../partner.sandbox'
import { PartnerUtilityService } from '../../shared/services/partner-utility.service'


@Component({
  selector: 'app-approved-table',
  templateUrl: './approved-table.component.html',
  styleUrls: ['./approved-table.component.scss']
})
export class ApprovedTableComponent implements OnInit {

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox :PartnerSandbox,
    public partnerUtilityService:PartnerUtilityService
  ) { }

  ngOnInit() {
    this.partnerDataService.licenseDetails.tab = 'approved'
    this.partnerDataService.licensePageDetails.sortBy = 'organization';
    this.partnerSandbox.getAllLicenses();  
  }


  showLicenseDetails(i):void{
    this.partnerDataService.approvePop.showPopup = true;
    this.partnerDataService.selectedCurrentLicenses =this.partnerDataService.getLicenseDetails.license[i]
  }

  selectTask(event):void{
    event.stopPropagation();
  }

  showPopup(event):void{
   // event.stopPropagation();
    this.partnerDataService.approvePop.showPopup = true;
   // this.partnerDataService.applyLicensePopup.show =true
  }
/*Sort licenses*/
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

ngOnDestroy(){
  this.partnerDataService.resetLicenseDetails();
}

}
