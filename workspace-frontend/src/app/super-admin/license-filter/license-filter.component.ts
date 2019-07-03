import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService} from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-license-filter',
  templateUrl: './license-filter.component.html',
  styleUrls: ['./license-filter.component.scss']
})
export class LicenseFilterComponent implements OnInit {
  partnerList: boolean = false;
  monthList: boolean = false;
  monthBefore: boolean = false;

  constructor(
    public superAdminDataService : SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  closeFilter(){
    this.superAdminDataService.licenseFilter.show = false;
  }

  closePartner(){
    this.partnerList = false;
  }

  showPartner(){
    this.partnerList =! this.partnerList;
  }

  showMonths(){
    this.monthList =! this.monthList;
  }

  showMonthBefore(){
    this.monthBefore =! this.monthBefore;
  }
}

