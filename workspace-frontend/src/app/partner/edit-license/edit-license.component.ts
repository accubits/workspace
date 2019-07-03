import { Component, OnInit } from '@angular/core';
import { PartnerDataService} from '../../shared/services/partner-data.service';
import { PartnerSandbox} from '../partner.sandbox'

@Component({
  selector: 'app-edit-license',
  templateUrl: './edit-license.component.html',
  styleUrls: ['./edit-license.component.scss']
})
export class EditLicenseComponent implements OnInit {
  toggleShow:boolean=false;
  orgShow:boolean=false;

  constructor(
    public partnerDataService : PartnerDataService,
    public partnerSandbox : PartnerSandbox,
  ) { }

  ngOnInit() {
  }

  /* Entering the selected organisations*/
     organisations(organisation) {
    this.partnerDataService.updateLicense.name = organisation.name;
    this.partnerDataService.updateLicense.orgSlug = organisation.orgSlug
  }

  /* edit Licenses*/

  editLicenses():void{
    if(!this.validateElement()){
      return;
    }

      this.partnerSandbox.updateLicenses();
  }

  /*Validating Element*/
  validateElement(): void {
    (!this.partnerDataService.updateLicense.maxUsers) ?
      this.partnerDataService.selectedElement.isValidated = false : this.partnerDataService.selectedElement.isValidated = true;
  return this.partnerDataService.selectedElement.isValidated;
  }


}
