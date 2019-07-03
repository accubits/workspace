import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-partner-table',
  templateUrl: './partner-table.component.html',
  styleUrls: ['./partner-table.component.scss']
})
export class PartnerTableComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }

  ngOnInit() {
  }
  morepop() {
 this.superAdminDataService.more.show = true;
  }
  closePop() {
    this.superAdminDataService.more.show = false;
     }
}
