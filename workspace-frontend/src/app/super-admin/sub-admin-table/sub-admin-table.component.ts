import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-sub-admin-table',
  templateUrl: './sub-admin-table.component.html',
  styleUrls: ['./sub-admin-table.component.scss']
})
export class SubAdminTableComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }

  ngOnInit() {
  }
  morepop() {
    this.superAdminDataService.more.show = true;
    event.stopPropagation();
  }
  closePop() {
    this.superAdminDataService.more.show = false;
  }
  showPermission() {
    this.superAdminDataService.permissionPop.show = true;
    event.stopPropagation();
  }
  showDetailPop() {
    this.superAdminDataService.subAdminDetailPop.show = true;
  }
}
