import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import {PartnerSandbox} from '../partner.sandbox'

@Component({
  selector: 'app-approved-pop',
  templateUrl: './approved-pop.component.html',
  styleUrls: ['./approved-pop.component.scss']
})
export class ApprovedPopComponent implements OnInit {

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox
  ) { }

  ngOnInit() {
    // for(let i=0; i<this.partnerDataService.getLicenseDetails.license.length;i++){
    //   this.partnerDataService.getLicenseDetails.license[i].selected = false;
    // }
    this.partnerDataService.selectedLicenseDetails.selectedLicenseIds = [] 
    this.partnerSandbox.getAllLicenses();

  }

  closepop(){
    this.partnerDataService.approvePop.showPopup = false;
   this.partnerDataService.cancelPop.showPopup = false;
  }

  applyLicenses():void{
    this.partnerSandbox.applyLicenses();
  }

  deleteLicenses():void{
    this.partnerDataService.cancelPop.showPopup = true; 

  }
}
