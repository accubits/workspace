import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-sub-admin-detail',
  templateUrl: './sub-admin-detail.component.html',
  styleUrls: ['./sub-admin-detail.component.scss']
})
export class SubAdminDetailComponent implements OnInit {
  currentTab = 'permissions';
  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }

  ngOnInit() {
  }
  hideDetailPop() {
    this.superAdminDataService.subAdminDetailPop.show = false;
  }

}
