import { Component, OnInit } from '@angular/core';
import { PartnerDataService } from '../../shared/services/partner-data.service'
import { PartnerSandbox } from '../partner.sandbox'


@Component({
  selector: 'app-delete-org-pop',
  templateUrl: './delete-org-pop.component.html',
  styleUrls: ['./delete-org-pop.component.scss']
})
export class DeleteOrgPopComponent implements OnInit {

  constructor(
    public partnerDataService: PartnerDataService,
    public partnerSandbox: PartnerSandbox,
  ) { }

  ngOnInit() {
  }


  cancelCheck():void{
    this.partnerDataService.cancelPop.showPopup = false; 
    this.partnerDataService.showDelete.showPopup = true;
  }


  deleteOrganisation() {
    this.partnerSandbox.deleteOrganisation();
    this.partnerDataService.cancelPop.showPopup = false; 
    this.partnerDataService.showDelete.showPopup = true;



  }
}
