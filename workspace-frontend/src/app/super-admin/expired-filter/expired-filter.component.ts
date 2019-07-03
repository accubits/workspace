import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-expired-filter',
  templateUrl: './expired-filter.component.html',
  styleUrls: ['./expired-filter.component.scss']
})
export class ExpiredFilterComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }
  partnerSelect: boolean = false;
  reqBefore: boolean = false;
  reqAfter: boolean = false;
  ngOnInit() {
  }
showPartnerSelect() {
  this.partnerSelect = !this.partnerSelect;
}
showReqBefore() {
  this.reqBefore = !this.reqBefore;
}
showReqAfter() {
  this.reqAfter = !this.reqAfter;
}
hideFilter() {
this.superAdminDataService.filterPopup.show = false;
}
}
