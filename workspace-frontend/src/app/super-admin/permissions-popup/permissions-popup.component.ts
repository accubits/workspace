import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-permissions-popup',
  templateUrl: './permissions-popup.component.html',
  styleUrls: ['./permissions-popup.component.scss']
})
export class PermissionsPopupComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }

  ngOnInit() {
  }
  hidepermission()  {
    this.superAdminDataService.permissionPop.show = false;
  }
}
