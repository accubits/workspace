import { LicenseSettingComponent } from './license-setting/license-setting.component';
import { OrganizationSettingComponent } from './organization-setting/organization-setting.component';
import { LicenseComponent } from './../super-admin/license/license.component';
import { OrganisationComponent } from './../partner/organisation/organisation.component';
import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SettingsComponent } from './settings.component';
import { ProfileComponent } from './profile/profile.component';
import { TwoFactorComponent } from './two-factor/two-factor.component';

const routes: Routes = [
  {
    path: '',
    component: SettingsComponent,
    children: [
      { path: '', pathMatch: 'full', redirectTo: 'app-profile' },
      { path: 'app-profile', component: ProfileComponent },
      { path: 'app-two-factor', component: TwoFactorComponent },
      { path: 'organization-setting', component: OrganizationSettingComponent },
      { path: 'license-setting', component: LicenseSettingComponent },


    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SettingsRoutingModule { }
