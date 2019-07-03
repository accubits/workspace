import { CrmDataService } from './../../shared/services/crm-data.service';
import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-customers',
  templateUrl: './customers.component.html',
  styleUrls: ['./customers.component.scss']
})
export class CustomersComponent implements OnInit {

  constructor(
    public router: Router,
    public crmDataService: CrmDataService,
  ) { }

  ngOnInit() {
  }
  showMore() {
    event.stopPropagation();
    this.crmDataService.moreOption.show = true;
  }

  showDetailPop() {
    this.crmDataService.customerDetail.show = true;
  }
  hideMore() {
    this.crmDataService.moreOption.show = false;
  }
}
