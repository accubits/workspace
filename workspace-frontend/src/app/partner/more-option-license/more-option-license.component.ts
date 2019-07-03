import { Component, OnInit,Input } from '@angular/core';
import { PartnerSandbox } from '../partner.sandbox'
import { PartnerDataService } from '../../shared/services/partner-data.service'

@Component({
  selector: 'app-more-option-license',
  templateUrl: './more-option-license.component.html',
  styleUrls: ['./more-option-license.component.scss']
})
export class MoreOptionLicenseComponent implements OnInit {
  @Input() Index: number


  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox: PartnerSandbox
  ) { }

  ngOnInit() {
  }


    /*  Renew Licenses */
    
  renewLicense(event,Index): void {
      event.stopPropagation();
      this.partnerDataService.renewLicensePopup.show = true; 
      this.partnerDataService.selectedCurrentLicenses = this.partnerDataService.getLicenseDetails.license[Index]
     this.partnerDataService.getLicenseDetails.license[Index]['showMore'] =false;
    }
  
    /*Cancel License Request*/
    
    cancelRequest(event,Index):void{
      this.partnerDataService.cancelRequestPop.showPopup = true;
      this.partnerDataService.selectedCurrentLicenses = this.partnerDataService.getLicenseDetails.license[Index]

    }

}
