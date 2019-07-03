import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { PartnerManagerComponent} from '../partner-manager/partner-manager.component';
import { PartnerContentComponent} from '../partner-manager/partner-content/partner-content.component';
import { SettingsComponent} from '../partner-manager/settings/settings.component';

const routes: Routes = [
  {
    path: '' , 
    component: PartnerManagerComponent,
    children: [
      { path: '' , redirectTo: 'partner-content', pathMatch: "full"},
      { path: 'partner-content', component: PartnerContentComponent},
      { path: 'settings', component: SettingsComponent}
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PartnerManagerRoutingModule { }
