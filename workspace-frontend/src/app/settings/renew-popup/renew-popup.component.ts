import { Component, OnInit } from '@angular/core';
import {SettingsDataService} from './../../shared/services/settings-data.service'
import {SettingsSandbox} from './../settings.sandbox'

@Component({
  selector: 'app-renew-popup',
  templateUrl: './renew-popup.component.html',
  styleUrls: ['./renew-popup.component.scss']
})
export class RenewPopupComponent implements OnInit {
  toggleShow: boolean = false;
  orgShow: boolean = false;
  constructor(
    public settingsDataService :SettingsDataService,
    public settingsSandbox:SettingsSandbox
  ) { }

  ngOnInit() {
  }

  toggleClose() {
    this.toggleShow = false;
  }

  typeOfLicense(type) {
   this.settingsDataService.updateRenewLicense.licenseType = type;
  }

  createLicenses(){
    if(!this.validateElement()){
      return;
    }
    this.settingsDataService.renewLicense.licenseKey=this.settingsDataService.getLicenseDetails.key

   this.settingsSandbox.renewLicenses();
  }

  /*Validating Element*/
  validateElement(){
    (!this.settingsDataService.updateRenewLicense.maxUsers ) ?
      this.settingsDataService.selectedElement.isValidated = false : this.settingsDataService.selectedElement.isValidated = true;
  return this.settingsDataService.selectedElement.isValidated;
  }

  closeCreatePopup() {
    this.settingsDataService.renewRequestPopup.show =false;
    this.settingsDataService.selectedElement.isValidated = true;


  }
}
