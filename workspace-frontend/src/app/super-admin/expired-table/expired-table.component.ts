import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-expired-table',
  templateUrl: './expired-table.component.html',
  styleUrls: ['./expired-table.component.scss']
})
export class ExpiredTableComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }

  ngOnInit() {
  }
  showDetails() {
    this.superAdminDataService.expiredDetail.show = true;
  }
}
