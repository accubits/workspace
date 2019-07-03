import { SuperAdminDataService } from './../../shared/services/super-admin-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-add-sub-admin',
  templateUrl: './add-sub-admin.component.html',
  styleUrls: ['./add-sub-admin.component.scss']
})
export class AddSubAdminComponent implements OnInit {

  dropdown = false;
  partner = false;
  constructor(
    public superAdminDataService: SuperAdminDataService,
  ) { }

  ngOnInit() {
  }
  hideAddSubAdmin() {
    this.superAdminDataService.createSubAdmin.show = false;
  }
  showDropdown() {
    this.dropdown = !this.dropdown;
  }
  hideDropdown() {
    this.dropdown = false;
  }
  showPartner() {
    this.partner = true;
  }

  hidePartner() {
    this.partner = false;
  }
}
