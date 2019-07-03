import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { SettingsDataService } from '../../shared/services/settings-data.service';
import { SettingsSandbox } from '../settings.sandbox';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.scss']
})
export class ProfileComponent implements OnInit {
  
  interestArray = [];
  public assetUrl = Configs.assetBaseUrl;
  constructor(
    public settingsDataService: SettingsDataService,
    public settingsSandbox: SettingsSandbox,
  ) { }

  ngOnInit() {
    // Initial executions
    this.settingsSandbox.fetchProfileDetailsEdit(); // Getting profile details
    // console.log(this.settingsDataService.editSettingsTemplate);
  }

  /* Edit Profile [Start] */
  editProfile(){
    this.settingsDataService.editProfile.show = true;
    this.settingsDataService.editProfile.first_name = this.settingsDataService.editSettingsTemplate.firstName;
    this.settingsDataService.editProfile.birth_date = this.settingsDataService.editSettingsTemplate.birthDate ;
    }
   /* Edit Profile [End] */

  /* Remove Interest [Start] */
    removeInterest(i) {
    this.interestArray.splice(i, 1);
  }
   /* Remove Interest [End] */

  }
