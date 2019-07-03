import { Component, OnInit } from '@angular/core';
import { SuperAdminSandbox } from '../super-admin.sandbox';
import { SuperAdminDataService } from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-country',
  templateUrl: './country.component.html',
  styleUrls: ['./country.component.scss']
})
export class CountryComponent implements OnInit {

  constructor(private superadminSandbox: SuperAdminSandbox,
    public superAdminDataService: SuperAdminDataService) { }

  ngOnInit() {
    this.superadminSandbox.getCountryList();
  }

   /* delete selected event [Start] */
   deleteCountry(slug , index) {
    this.superAdminDataService.optionBtn[index] = false;
    this.superAdminDataService.countryDetails.slug = slug;
    this.superAdminDataService.countryDetails.action = 'delete';
    this.superAdminDataService.deleteMessage.msg = 'Are you sure you want to delete selected Country?'
    this.superAdminDataService.deletePopUp.show = true;
  }
  conformCountryDelete() {
    this.superadminSandbox.setCountry();
  }
  cancelDelete() {
    this.superAdminDataService.deletePopUp.show = false;
  }
  /* delete selected event [end] */

  update(Country, index){
    this.superAdminDataService.countryDetails.slug = Country.slug;
    this.superAdminDataService.countryDetails.action = 'update';
    this.superAdminDataService.countryDetails.name = Country.name;
    this.superAdminDataService.createCountry.show = true;
    this.superAdminDataService.optionBtn[index] = false;

  }

  showPop(){
    this.superAdminDataService.countryDetails.slug = null;
    this.superAdminDataService.countryDetails.action = 'create';
    this.superAdminDataService.countryDetails.name = '';
    this.superAdminDataService.createCountry.show = true;
  }
}
