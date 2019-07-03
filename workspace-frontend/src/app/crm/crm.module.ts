import { SharedModule } from './../shared/shared.module';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CrmRoutingModule } from './crm-routing.module';
import { CrmContainerComponent } from './crm-container/crm-container.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { LeadsComponent } from './leads/leads.component';
import { MoreOptionComponent } from './more-option/more-option.component';
import {CrmSandbox} from './crm.sandbox';
import { LeadsDetailComponent } from './leads-detail/leads-detail.component';
import { LeadsInfoComponent } from './leads-info/leads-info.component';
import { LeadsNotesComponent } from './leads-notes/leads-notes.component';
import { LeadsTaskComponent } from './leads-task/leads-task.component';
import { LeadsEventComponent } from './leads-event/leads-event.component';
import { CustomersComponent } from './customers/customers.component';
import { CustomersDetailComponent } from './customers-detail/customers-detail.component';
import { EditProfileComponent } from './edit-profile/edit-profile.component';
import { SettingsComponent } from './settings/settings.component';
@NgModule({
  imports: [
    CommonModule,
    CrmRoutingModule,
    SharedModule
  ],
  providers: [CrmSandbox],
  declarations: [CrmContainerComponent, DashboardComponent, LeadsComponent, MoreOptionComponent, LeadsDetailComponent, LeadsInfoComponent, LeadsNotesComponent, LeadsTaskComponent, LeadsEventComponent, CustomersComponent, CustomersDetailComponent, EditProfileComponent, SettingsComponent]
})
export class CrmModule { }
