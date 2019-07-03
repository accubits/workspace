import { CrmDataService } from './../../shared/services/crm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-customers-detail',
  templateUrl: './customers-detail.component.html',
  styleUrls: ['./customers-detail.component.scss']
})
export class CustomersDetailComponent implements OnInit {

  constructor(
    public crmDataService: CrmDataService,
  ) { }
  currentTab = 'actiivity';
  button = false;
  ngOnInit() {
  }
  hideDetailPop() {
    this.crmDataService.customerDetail.show = false;
  }
  showEditPop() {
    this.crmDataService.editProfile.show = true;
  }
  showBtn() {
    this.button = true;
  }
  hideBtn() {
    this.button = false;
  }
}
