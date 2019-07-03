import { Component, OnInit } from '@angular/core';
import { SuperAdminSandbox } from '../super-admin.sandbox';
import { SuperAdminDataService } from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-license-content',
  templateUrl: './license-content.component.html',
  styleUrls: ['./license-content.component.scss']
})
export class LicenseContentComponent implements OnInit {

  constructor(
    private superadminSandbox: SuperAdminSandbox,
    public superAdminDataService: SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  morepop(event){
    this.superAdminDataService.more.show = true;
    event.stopPropagation();
  }

  showpop(){
    this.superAdminDataService.licenseDetailsPop.showPopup = true;
  }

  check(event){
    event.stopPropagation();
  }


  closeMore(event){
    this.superAdminDataService.more.show = false;
    event.stopPropagation();
  }
}
