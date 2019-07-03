import { Component, OnInit } from '@angular/core';
import { PartnerDataService } from '../../shared/services/partner-data.service'
import {PartnerSandbox} from '../partner.sandbox'



@Component({
  selector: 'app-delete-confirm',
  templateUrl: './delete-confirm.component.html',
  styleUrls: ['./delete-confirm.component.scss']
})
export class DeleteConfirmComponent implements OnInit {

  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox:PartnerSandbox


  ) { }

  ngOnInit() {
    this.partnerDataService.selectedLicenseDetails.selectedLicenseIds = [] 

  }

  closePopup():void{
    this.partnerDataService.cancelRequestPop.showPopup = false;

  }

  cancelPopup():void{
    this.partnerDataService.cancelRequestPop.showPopup = false;
  }

  deleteRequest(){

    if(this.partnerDataService.licenseDetails.tab == 'adminLicenseRequests'||this.partnerDataService.licenseDetails.tab == 'licenseRequests'){
    this.partnerDataService.selectedRequest.licenseSlug = this.partnerDataService.selectedCurrentLicenses.licenseRequestSlug
    this.partnerSandbox.deleteLicenseRequest();
     }


  }

}
