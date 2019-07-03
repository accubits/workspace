import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from '../shared/shared.module';
import { SettingsRoutingModule } from './settings-routing.module';
import { SettingsComponent } from './settings.component';
import { SettingsNavComponent } from './settings-nav/settings-nav.component';
import { ProfileComponent } from './profile/profile.component';
import { TwoFactorComponent } from './two-factor/two-factor.component';
import { EditProfileComponent } from './edit-profile/edit-profile.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ChangePasswordComponent } from './change-password/change-password.component';
import { SettingsSandbox } from './settings.sandbox';
import {HrmSandboxService} from '../hrm/hrm.sandbox'
import { OrganizationSettingComponent } from './organization-setting/organization-setting.component';
import { LicenseSettingComponent } from './license-setting/license-setting.component';
import { LicenseRenewComponent } from './license-renew/license-renew.component';
import { LicenseHistoryComponent } from './license-history/license-history.component';
import { RenewPopupComponent } from './renew-popup/renew-popup.component';



@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    SharedModule,
    SettingsRoutingModule
  ],

  providers: [SettingsSandbox,HrmSandboxService],

  declarations: [
    SettingsComponent,
     SettingsNavComponent,
      ProfileComponent, TwoFactorComponent, EditProfileComponent,
      ChangePasswordComponent, OrganizationSettingComponent, LicenseSettingComponent, LicenseRenewComponent, LicenseHistoryComponent, RenewPopupComponent]
})
export class SettingsModule { }
