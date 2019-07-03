import { CrmDataService } from './../../shared/services/crm-data.service';
import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-leads',
  templateUrl: './leads.component.html',
  styleUrls: ['./leads.component.scss']
})
export class LeadsComponent implements OnInit {

  constructor(
    public router: Router,
    public crmDataService: CrmDataService,
  ) { }

  ngOnInit() {
  }
  showMore() {
    this.crmDataService.moreOption.show = true;
  }
  hideMore() {
    this.crmDataService.moreOption.show = false;
  }
  showDetailPop() {
    this.crmDataService.leadsDetail.show = true;
  }
}
