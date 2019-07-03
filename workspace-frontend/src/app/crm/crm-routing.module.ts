import { LeadsComponent } from './leads/leads.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CrmContainerComponent } from './crm-container/crm-container.component';
import { CustomersComponent } from './customers/customers.component';
import { SettingsComponent } from './settings/settings.component';


const routes: Routes = [
  {
    path: '',
    component: CrmContainerComponent,
    children: [
      { path: '', redirectTo: 'dashboard', pathMatch: 'full'},
      { path: 'dashboard',  component: DashboardComponent},
      { path: 'leads', component: LeadsComponent},
      { path: 'customers', component: CustomersComponent},
      { path: 'settings', component: SettingsComponent},
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CrmRoutingModule { }
