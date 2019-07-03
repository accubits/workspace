import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService} from '../../shared/services/super-admin-data.service'

@Component({
  selector: 'app-license-details-pop',
  templateUrl: './license-details-pop.component.html',
  styleUrls: ['./license-details-pop.component.scss']
})
export class LicenseDetailsPopComponent implements OnInit {
  //public currentTab : string = '';
  public currentTab : string = 'current' ;

  constructor(
    public superAdminDataService : SuperAdminDataService
  ) { }


  ngOnInit() {
  }

  closeOrgDetails(){
    this.superAdminDataService.licenseDetailsPop.showPopup = false;
  }

}
