import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-add-partner',
  templateUrl: './add-partner.component.html',
  styleUrls: ['./add-partner.component.scss']
})
export class AddPartnerComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }
  vertical = false;
  dropdown = false;
  ngOnInit() {
  }
  hidePartner() {
  this.superAdminDataService.addPartner.show = false;
  }
  showDropdown() {
    this.dropdown = ! this.dropdown;
  }
  hideDropdown() {
    this.dropdown = false;
  }
  showVertical() {
    this.vertical = !this.vertical;
  }
  hideVertical() {
    this.vertical = false;
  }
}
