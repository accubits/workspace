import { Component, OnInit } from '@angular/core';
import { PartnerDataService } from '../../shared/services/partner-data.service';
import {PartnerSandbox} from '../partner.sandbox'


@Component({
  selector: 'app-delete-org-confirm',
  templateUrl: './delete-org-confirm.component.html',
  styleUrls: ['./delete-org-confirm.component.scss']
})
export class DeleteOrgConfirmComponent implements OnInit {

  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox:PartnerSandbox
  ) { }

  ngOnInit() {
  }
  closePopup():void{
    this.partnerDataService.cancelRequestPop.showPopup = false;
    this.partnerDataService.deleteBulkPopup.show =false;

  }

  cancelPopup():void{
    this.partnerDataService.cancelRequestPop.showPopup = false;
    this.partnerDataService.deleteBulkPopup.show =false;

  }

  deleteOrganisation(){
      this.partnerSandbox.deleteOrganisation();

  }

  deleteBulkOrganisation():void{
    this.partnerSandbox.deleteAllOrganisation();
  }

}
