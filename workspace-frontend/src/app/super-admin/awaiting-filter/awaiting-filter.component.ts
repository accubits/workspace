import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-awaiting-filter',
  templateUrl: './awaiting-filter.component.html',
  styleUrls: ['./awaiting-filter.component.scss']
})
export class AwaitingFilterComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }
  partnerSelect: boolean = false;
  reqAfter: boolean = false;
  reqBefore: boolean = false;

  ngOnInit() {
  }
  hideAwaitingFilter() {
    this.superAdminDataService.awaitingFilter.show = false;
  }
  showPartnerSelect() {
    this.partnerSelect = !this.partnerSelect;
  }
  showReqAfter() {
    this.reqAfter = !this.reqAfter;
  }
  showReqBefore() {
    this.reqBefore = !this.reqBefore;
  }
}
