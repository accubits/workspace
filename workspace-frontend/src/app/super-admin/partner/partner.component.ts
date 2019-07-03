import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-partner',
  templateUrl: './partner.component.html',
  styleUrls: ['./partner.component.scss']
})
export class PartnerComponent implements OnInit {

  constructor(
    public router: Router,
    public superAdminDataService: SuperAdminDataService,
  ) { }

  ngOnInit() {
  }
  showPartner() {
    this.superAdminDataService.addPartner.show = true;
  }
}
