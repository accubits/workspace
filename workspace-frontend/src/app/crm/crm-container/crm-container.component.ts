import { CrmDataService } from './../../shared/services/crm-data.service';
import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-crm-container',
  templateUrl: './crm-container.component.html',
  styleUrls: ['./crm-container.component.scss']
})
export class CrmContainerComponent implements OnInit {

  constructor(
    public router: Router,
    public crmDataService: CrmDataService,
  ) { }
  showSearch = false;
  process = false;
  ngOnInit() {
  }
  searchClose() {
    this.showSearch = false;
  }
  showProcess() {
    this.process = true;
  }
  hideProcess() {
    this.process = false;
  }
  showNewLead() {
    this.crmDataService.editProfile.show = true;
  }
}
