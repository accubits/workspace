import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { PartnerComponent } from './partner.component';
import{ OrganisationComponent} from './organisation/organisation.component'
import{ CreateOrgComponent} from './create-org/create-org.component';
import{ LicenseComponent} from './license/license.component';
import{ CreateLicenseComponent} from './create-license/create-license.component';
// import{ PartnerManagerComponent} from './partner-manager/partner-manager.component';
import{ PartnerSettingsComponent} from './partner-settings/partner-settings.component';
import{ ProfileComponent} from './profile/profile.component';
import{ ApprovedTableComponent} from './approved-table/approved-table.component'
import{OrgTableComponent} from './org-table/org-table.component';
import{UnlicenseTableComponent}from './unlicense-table/unlicense-table.component';
import{LicensedOrgTableComponent} from './licensed-org-table/licensed-org-table.component';

import{LicenseTableComponent} from './license-table/license-table.component';
import{ RequestTableComponent} from './request-table/request-table.component';
import{ExpiredTableComponent} from './expired-table/expired-table.component';
import{AdminTableComponent} from './admin-table/admin-table.component';

const routes: Routes = [
  {
    path: '',
    component: PartnerComponent,
    children: [
      { path: '', redirectTo: 'organisation', pathMatch:"full"},
      { path: 'organisation',  component: OrganisationComponent,
      
      children: [

        {path:'', redirectTo: 'show-org', pathMatch:"full"},
        {path:'show-org',component: OrgTableComponent},
        {path:'unlicensed-table',component: UnlicenseTableComponent },
        {path:'licensed-org-table',component: LicensedOrgTableComponent },
      ]
      },
      { path: 'create-org', component: CreateOrgComponent},
      { path: 'license' , component: LicenseComponent,

        children: [
          {path:'', redirectTo: 'current-license', pathMatch:"full"},
          {path: 'current-license',component:LicenseTableComponent},
          {path:'request',component: RequestTableComponent},
          {path:'approved', component: ApprovedTableComponent},
          {path:'expired',component:ExpiredTableComponent},
          {path:'admin-request',component:AdminTableComponent},
        ]
    },
      
      { path: 'create-license', component: CreateLicenseComponent},
      // { path: 'partner-manager', component: PartnerManagerComponent},
      { path: 'partner-settings', component: PartnerSettingsComponent},
      { path: 'profile', component: ProfileComponent},
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PartnerRoutingModule { }
