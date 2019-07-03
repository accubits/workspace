import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from '../shared/shared.module';
import { PartnerManagerRoutingModule } from './partner-manager-routing.module';
import { PartnerManagerComponent } from './partner-manager.component';
import { HeaderComponent } from './header/header.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { PartnerContentComponent } from './partner-content/partner-content.component';
import {PartnerManagerSandbox} from './partner-manager.sandbox';
import { SettingsComponent } from './settings/settings.component';
import { SettingsHeadComponent } from './settings-head/settings-head.component';
import { ProfileComponent } from './profile/profile.component';
import { TwoFactorComponent } from './two-factor/two-factor.component';
import { EditProfileComponent } from './edit-profile/edit-profile.component';
import { ChangePasswordComponent } from './change-password/change-password.component';
import {SettingsSandbox} from '../settings/settings.sandbox'


@NgModule({
  imports: [
    CommonModule,
    PartnerManagerRoutingModule,
    SharedModule,
    PerfectScrollbarModule
  ],
  providers: [PartnerManagerSandbox,SettingsSandbox],
  declarations: [PartnerManagerComponent, HeaderComponent, SidebarComponent, PartnerContentComponent, SettingsComponent, SettingsHeadComponent, ProfileComponent, TwoFactorComponent, EditProfileComponent, ChangePasswordComponent]
})
export class PartnerManagerModule { }
