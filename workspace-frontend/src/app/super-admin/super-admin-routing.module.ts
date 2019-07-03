import { AwaitingActivationComponent } from './awaiting-activation/awaiting-activation.component';
import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SuperAdminComponent } from './super-admin.component';
import { LicenseComponent } from './license/license.component';
import { CountryComponent} from './country/country.component';
import { VerticalComponent} from './vertical/vertical.component';
import { RequestTableComponent} from './request-table/request-table.component';
import { LicenseContentComponent } from './license-content/license-content.component';
import { ExpiredTableComponent} from './expired-table/expired-table.component';
import { SubAdminComponent } from './sub-admin/sub-admin.component';
import { SettingsComponent } from './settings/settings.component';
import { OrganizationComponent} from './organization/organization.component';
import { FormsComponent } from './forms/forms.component';
import { FormPublishedComponent } from './form-published/form-published.component';
import { FormDraftComponent } from './form-draft/form-draft.component';
import { FormInactiveComponent } from './form-inactive/form-inactive.component';
import { PartnerComponent } from './partner/partner.component';


const routes: Routes = [
  {
     path: '',
     component: SuperAdminComponent ,
     children: [
      { path: '', redirectTo: 'license', pathMatch: 'full'},
      { path: 'license', component: LicenseComponent,

        children: [
          { path: '',  redirectTo: 'current-license', pathMatch: 'full'},
          { path: 'current-license', component: LicenseContentComponent},
          { path: 'request', component: RequestTableComponent},
          { path: 'awaiting-activation', component: AwaitingActivationComponent},
          { path: 'expired', component: ExpiredTableComponent}
        ]
      },
      { path: 'organization' , component: OrganizationComponent},
      { path: 'forms', component: FormsComponent,

        children: [
          { path: '', redirectTo: 'form-published', pathMatch: 'full'},
          { path: 'form-published', component: FormPublishedComponent},
          { path: 'form-draft', component: FormDraftComponent},
          { path: 'form-inactive', component: FormInactiveComponent}
        ]
      },
      { path: 'country', component: CountryComponent},
      { path: 'sub-admin', component: SubAdminComponent},
      { path: 'vertical', component: VerticalComponent},
      { path: 'settings', component: SettingsComponent},
      { path: 'partner', component: PartnerComponent},
     ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class SuperAdminRoutingModule { }
