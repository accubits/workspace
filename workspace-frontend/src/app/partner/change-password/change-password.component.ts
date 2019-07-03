import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { FormsModule } from '@angular/forms';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { SettingsDataService } from '../../shared/services/settings-data.service';
 import { SettingsSandbox } from '../../settings/settings.sandbox';


@Component({
  selector: 'app-change-password',
  templateUrl: './change-password.component.html',
  styleUrls: ['./change-password.component.scss']
})
export class ChangePasswordComponent implements OnInit {

  isValidated: boolean = true;
  public assetUrl = Configs.assetBaseUrl;
  confirmPwd = '';
  error: string;
  // strongRegex = "^(?=.{8,})";

  constructor(
    public settingsDataService: SettingsDataService,
    public settingsSandbox: SettingsSandbox,
    private spinner: Ng4LoadingSpinnerService
  ) { }

  ngOnInit() {
    this.error = '';

  }

  /* Validating Change Password[Start] */
  validatePassword(): boolean {
    this.isValidated = true;
    // Validating old password and new password
    if (!this.settingsDataService.changePassword.oldPassword) this.isValidated = false;
    if (!this.settingsDataService.changePassword.newPassword) this.isValidated = false;
    if (this.settingsDataService.changePassword.newPassword !== this.settingsDataService.changePassword.confirmPwd) 
    {
       this.isValidated = false;
        // this.error = 'Password does not match';
        return;
    }

    

    // if (!this.strongRegex.test(this.settingsDataService.changePassword.newPassword)){
    //   this.error = 'Password must contain at least 8 characters';
    //   return;
    // }

    return this.isValidated;
  }
    /* Validating Change Password[End] */

    /* Change Password*/

  changePassword(): void {
    if (!this.validatePassword()) return;
    this.spinner.show();
     this.settingsSandbox.changePassword();
  }

  ngOnDestroy() {
   this.settingsDataService.resetPassword();
  }
}
