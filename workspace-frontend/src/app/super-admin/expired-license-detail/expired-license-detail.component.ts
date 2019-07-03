import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-expired-license-detail',
  templateUrl: './expired-license-detail.component.html',
  styleUrls: ['./expired-license-detail.component.scss']
})
export class ExpiredLicenseDetailComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }
type = 'detail';
  ngOnInit() {
  }
  hideDetails() {
    this.superAdminDataService.expiredDetail.show = false;
  }
}
