import { SettingsDataService } from './../../shared/services/settings-data.service';
import {PartnerDataService} from './../../shared/services/partner-data.service'
import { SettingsSandbox }from '../settings.sandbox'
import { SettingsModule } from './../settings.module';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-license-setting',
  templateUrl: './license-setting.component.html',
  styleUrls: ['./license-setting.component.scss']
})
export class LicenseSettingComponent implements OnInit {

  constructor(
    public settingsDataService: SettingsDataService,
    public settingsSandbox: SettingsSandbox,
    public partnerDataService:PartnerDataService
  ) { }

  ngOnInit() {
    this.settingsSandbox.getLicense();
  }
  showRenewPop() {
    this.settingsDataService.renewLicence.show = true;
  }
  showLicenseHistory() {
    this.settingsDataService.licenseHistory.show = true;
  }

  renewLicenseRequests(){
    this.settingsDataService.renewRequestPopup.show = true;  
     this.settingsDataService.updateRenewLicense.name =this.settingsDataService.getLicenseDetails.orgName
     this.settingsDataService.updateRenewLicense.licenseType =this.settingsDataService.getLicenseDetails.type
   this.settingsDataService.updateRenewLicense.maxUsers =this.settingsDataService.getLicenseDetails.users.totalUsers
  }
}
