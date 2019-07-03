import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-awaiting-detail',
  templateUrl: './awaiting-detail.component.html',
  styleUrls: ['./awaiting-detail.component.scss']
})
export class AwaitingDetailComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }
 type = 'detail';
  ngOnInit() {
  }
  hideDetails() {
    this.superAdminDataService.awaitingDetail.show = false;
  }
}
