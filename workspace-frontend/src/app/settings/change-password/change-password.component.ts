import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { FormsModule } from '@angular/forms';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { SettingsDataService } from '../../shared/services/settings-data.service';
import { SettingsSandbox } from '../settings.sandbox';


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

  constructor(
    public settingsDataService: SettingsDataService,
    private settingsSandbox: SettingsSandbox,
    private spinner: Ng4LoadingSpinnerService
  ) { }

  ngOnInit() {
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
      return;    }

    return this.isValidated;
  }

  changePassword(): void {
    if (!this.validatePassword()) return;
    this.spinner.show();
    this.settingsSandbox.changePassword();
  }
  /* Validating Change Password[End] */

  ngOnDestroy() {
    this.settingsDataService.resetPassword();
   }
}
