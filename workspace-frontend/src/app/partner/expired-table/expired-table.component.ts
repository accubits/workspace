import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import { PartnerSandbox} from '../partner.sandbox'
import { PartnerUtilityService } from '../../shared/services/partner-utility.service'



@Component({
  selector: 'app-expired-table',
  templateUrl: './expired-table.component.html',
  styleUrls: ['./expired-table.component.scss']
})
export class ExpiredTableComponent implements OnInit {
  moreOptions : boolean = false;


  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,
    public partnerUtilityService:PartnerUtilityService

  ) { }

  ngOnInit() {
    this.partnerDataService.licenseDetails.tab = 'expired'
    this.partnerDataService.licensePageDetails.sortBy = 'organization';
    this.partnerSandbox.getAllLicenses();
  }

  showMoreOption(event,i){
    event.stopPropagation();
    //this.moreOptions = !this.moreOptions;
    this.partnerDataService.getLicenseDetails.license[i]['showMore'] =  !this.partnerDataService.getLicenseDetails.license[i]['showMore'];

  }

  closeLicenseMore():void{
    this.moreOptions = false;
  }

  selectTask(event):void{
    event.stopPropagation();
  }


  showLicenseDetails(i):void{
    this.partnerDataService.renewPopup.showPopup = true; 
    this.partnerDataService.selectedCurrentLicenses =this.partnerDataService.getLicenseDetails.license[i]

  }


  ngOnDestroy(){
    this.partnerDataService.resetLicenseDetails();
  }

}
