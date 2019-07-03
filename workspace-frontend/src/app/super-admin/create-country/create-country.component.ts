import { Component, OnInit } from '@angular/core';
import { SuperAdminSandbox } from '../super-admin.sandbox';
import { SuperAdminDataService } from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-create-country',
  templateUrl: './create-country.component.html',
  styleUrls: ['./create-country.component.scss']
})
export class CreateCountryComponent implements OnInit {
  isValidated = true;
  constructor( 
    private superadminSandbox: SuperAdminSandbox,
    public superAdminDataService: SuperAdminDataService
  ) { }

  ngOnInit() {
  }

  create(){
    if (!this.validation()) return;
    this.superAdminDataService.countryDetails.slug = null;
    this.superAdminDataService.countryDetails.action = 'create';
    this.superAdminDataService.countryDetails.isActive = true;
    this.superadminSandbox.setCountry();
  }

  update(){
   this.superadminSandbox.setCountry();
  }

  closePop(){
    this.superAdminDataService.createCountry.show = false;
  }

  validation(): boolean {
    this.isValidated = true;
    if (!this.superAdminDataService.countryDetails.name) this.isValidated = false;
    return this.isValidated;
  }
}
