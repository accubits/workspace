import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-awaiting-activation',
  templateUrl: './awaiting-activation.component.html',
  styleUrls: ['./awaiting-activation.component.scss']
})
export class AwaitingActivationComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }

  ngOnInit() {
  }
  showDetail() {
    this.superAdminDataService.awaitingDetail.show = true;
  }
}
