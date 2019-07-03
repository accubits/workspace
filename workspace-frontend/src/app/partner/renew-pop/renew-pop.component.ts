import { Component, OnInit } from '@angular/core';
import { PartnerSandbox } from '../partner.sandbox'
import { PartnerDataService } from '../../shared/services/partner-data.service'

@Component({
  selector: 'app-renew-pop',
  templateUrl: './renew-pop.component.html',
  styleUrls: ['./renew-pop.component.scss']
})
export class RenewPopComponent implements OnInit {
  toggleShow: boolean = false;
  orgShow: boolean = false;

  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox: PartnerSandbox
  ) { }

  ngOnInit() {
    this.partnerDataService.createLicense.licenseType ='Annual'

  }

  toggleClose(){
    this.toggleShow= false;
  }

  typeOfLicense(type) {
    this.partnerDataService.createLicense.licenseType = type;
  }

  createLicenses(){
    if(!this.validateElement()){
      return;
    }

    if(this.partnerDataService.licenseDetails.tab = 'currentLicenses'){
      this.partnerDataService.renewLicense.licenseKey=this.partnerDataService.selectedCurrentLicenses.key
    }
    else{
      this.partnerDataService.renewLicense.licenseKey=this.partnerDataService.getOrgLicenseDetails.key

    }

    this.partnerSandbox.renewLicenses();
  }


  /*validate maxusers*/
  validateElement(){
    (!this.partnerDataService.updateRenewLicense.maxUsers)?
    this.partnerDataService.selectedElement.isValidated=false:this.partnerDataService.selectedElement.isValidated=true;
    return this.partnerDataService.selectedElement.isValidated;
  }
  
  closeCreatePopup() {
    this.partnerDataService.renewRequestPopup.show = false;
    this.partnerDataService.selectedElement.isValidated=true;

  }
}
