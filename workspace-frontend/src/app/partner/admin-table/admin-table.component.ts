import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import { PartnerSandbox} from '../partner.sandbox'
import { PartnerUtilityService } from '../../shared/services/partner-utility.service'


@Component({
  selector: 'app-admin-table',
  templateUrl: './admin-table.component.html',
  styleUrls: ['./admin-table.component.scss']
})
export class AdminTableComponent implements OnInit {
  moreOptions : boolean = false;


  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,
    public partnerUtilityService:PartnerUtilityService

  ) { }

  ngOnInit() {
    // Setting current tab and getting license list
    this.partnerDataService.licenseDetails.tab = 'adminLicenseRequests'
    this.partnerDataService.licensePageDetails.sortBy = 'requestedOn';
    this.partnerSandbox.getAllLicenses();
  }

  selectTask(event):void{
    event.stopPropagation();
  }

  /* Showing selected admin req details */
  showRequestDetails(i): void {
    this.partnerDataService.licenseRequestDetailsPop.show = true;
    this.partnerDataService.selectedAdminRequests = this.partnerDataService.getLicenseDetails.license[i]
  }

  showMoreOption(event,i){
    event.stopPropagation();
    //this.moreOptions = !this.moreOptions;
    this.partnerDataService.getLicenseDetails.license[i]['showMore'] =  !this.partnerDataService.getLicenseDetails.license[i]['showMore'];

  }


  closeLicenseMore():void{
    this.moreOptions = false;
  }

  ngOnDestroy(){
    this.partnerDataService.resetLicenseDetails();
  }

}
