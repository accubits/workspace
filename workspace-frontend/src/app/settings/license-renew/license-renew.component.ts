import { SettingsDataService } from './../../shared/services/settings-data.service';
import {SettingsSandbox} from './../settings.sandbox';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-license-renew',
  templateUrl: './license-renew.component.html',
  styleUrls: ['./license-renew.component.scss']
})
export class LicenseRenewComponent implements OnInit {

  constructor(
    public settingsDataService: SettingsDataService,
   public settingsSandbox: SettingsSandbox
  ) { }
  periodList: boolean = false;
  ngOnInit() {
    this.settingsDataService.createLicense.licenseType = 'Annual';

  }
  showPeriod() {
    this.periodList = true;
  }
  hidePeriod() {
    this.periodList = false;
  }
  hideRenewPop() {
    this.settingsDataService.resetLicenses();
    this.settingsDataService.selectedElement.isValidated = true;
    this.settingsDataService.renewLicence.show = false;
  }


  typeOfLicense(type) {
    this.settingsDataService.createLicense.licenseType = type;
  }

  requestLicense(){
    
    if (!this.validate()) {
      return;
    };
  this.settingsSandbox.createLicense();
  }

  validate() {
    (!this.settingsDataService.createLicense.maxUsers || !this.settingsDataService.createLicense.licenseType) ?
      this.settingsDataService.selectedElement.isValidated = false : this.settingsDataService.selectedElement.isValidated = true;
    return this.settingsDataService.selectedElement.isValidated;
  }

  ngOnDestroy(){
    this.settingsDataService.resetLicenses();
  }

}
