import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-sub-admin-head',
  templateUrl: './sub-admin-head.component.html',
  styleUrls: ['./sub-admin-head.component.scss']
})
export class SubAdminHeadComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }

  ngOnInit() {
  }
  addSubAdmin() {
    this.superAdminDataService.createSubAdmin.show = true;

  }
}
