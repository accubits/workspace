import { CrmDataService } from './../../shared/services/crm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-leads-detail',
  templateUrl: './leads-detail.component.html',
  styleUrls: ['./leads-detail.component.scss']
})
export class LeadsDetailComponent implements OnInit {
  currentTab = 'actiivity';
  constructor(
    public crmDataService: CrmDataService,
  ) { }
  process = false;
  button = false;
  accordion = false;
  isValidated = false;
  ngOnInit() {
  }
  showProcess() {
    this.process = true;
  }
  hideProcess() {
    this.process = false;
  }
  showBtn() {
    this.button = true;
  }
  hideBtn() {
    this.button = false;
  }
  hideDetailPop() {
    this.crmDataService.leadsDetail.show = false;
  }
  showHide() {
    this.accordion = !this.accordion;
    this.isValidated = !this.isValidated;
  }
  showEditPop() {
    this.crmDataService.editProfile.show = true;
  }
}
