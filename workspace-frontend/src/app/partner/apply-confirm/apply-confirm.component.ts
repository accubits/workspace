import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service'
import {PartnerSandbox} from '../partner.sandbox'

@Component({
  selector: 'app-apply-confirm',
  templateUrl: './apply-confirm.component.html',
  styleUrls: ['./apply-confirm.component.scss']
})
export class ApplyConfirmComponent implements OnInit {

  constructor(
    public partnerDataService:PartnerDataService,
    public partnerSandbox : PartnerSandbox

  )
   { }

  ngOnInit() {
  }

  cancelPopup():void{
    this.partnerDataService.applyLicensePopup.show =false;
  }

  closePopup():void{
    this.partnerDataService.applyLicensePopup.show =false;
  }

  applyLicense():void{
    this.partnerSandbox.applyLicenses();

  }

}
