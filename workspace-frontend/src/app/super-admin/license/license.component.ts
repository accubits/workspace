import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService} from '../../shared/services/super-admin-data.service'

@Component({
  selector: 'app-license',
  templateUrl: './license.component.html',
  styleUrls: ['./license.component.scss']
})
export class LicenseComponent implements OnInit {

  constructor(
     public superAdminDataService: SuperAdminDataService,
     public router: Router,
     ) { }

  ngOnInit() {
  }

  filterPop(){
    this.superAdminDataService.licenseFilter.show = true;
  }

  showCreate() {
    this.superAdminDataService.createLicense.show = true;
  }

  showFilter() {
    this.superAdminDataService.filterPopup.show = true;
  }

  showAwaitingFilter() {
    this.superAdminDataService.awaitingFilter.show = true;
  }

  requestFilter(){
    this.superAdminDataService.requestFilter.show = true;
  }
}
