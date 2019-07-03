import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService} from '../../shared/services/super-admin-data.service'

@Component({
  selector: 'app-create-license',
  templateUrl: './create-license.component.html',
  styleUrls: ['./create-license.component.scss']
})
export class CreateLicenseComponent implements OnInit {
  toggleShow: boolean = false;
  orgShow: boolean = false;
  organisationName: '';
  partnerPop: boolean = false;

  constructor(
    public superAdminDataService: SuperAdminDataService
  ) { }

  ngOnInit() {
    // Inital Executions 
  }

  closeCreatePopup(){
    this.superAdminDataService.createLicense.show = false;
  }
  toggleClose(){
    this.toggleShow = false;
    this.partnerPop = false;
    this.orgShow = false;
  }
  partnerShow(){
    this.partnerPop =! this.partnerPop;
  }
  organisation(){
    this.orgShow =! this.orgShow;
  }
  selectOrganisation(organisation) {
    this.organisationName = organisation.name;
  }


}
