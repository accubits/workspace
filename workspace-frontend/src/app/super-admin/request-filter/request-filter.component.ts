import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService} from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-request-filter',
  templateUrl: './request-filter.component.html',
  styleUrls: ['./request-filter.component.scss']
})
export class RequestFilterComponent implements OnInit {
  partnerList: boolean = false;
  monthList: boolean = false;
  monthBefore: boolean = false;


  constructor(
    public superAdminDataService : SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  closeFilter(){
    this.superAdminDataService.requestFilter.show = false;
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

  closePartner(){
    this.partnerList = false;
  }

  closeMonth(){
    this.monthList = false;
  }

  closeMonthBefore(){
    this.monthBefore = false;
  }
}
