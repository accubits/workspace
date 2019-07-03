import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AuthGuardGuard } from './shared/services/auth-guard.guard';

const routes: Routes = [
  {
    path: '',
    redirectTo: 'auth',
    pathMatch: 'full'
  },
  {
    path: 'auth',
    loadChildren: 'app/authentication/authentication.module#AuthenticationModule'
  },
  {
    path: 'authorized',
    canActivate: [AuthGuardGuard],
    loadChildren: 'app/authorized/authorized.module#AuthorizedModule'
  },
  {
    path: 'partner',
    canActivate: [AuthGuardGuard],
    loadChildren: 'app/partner/partner.module#PartnerModule'
  },
  {
    path: 'partner-manager',
    canActivate: [AuthGuardGuard],
    loadChildren: 'app/partner-manager/partner-manager.module#PartnerManagerModule'
  },
  {
    path: 'super-admin',
    canActivate: [AuthGuardGuard],
    loadChildren: 'app/super-admin/super-admin.module#SuperAdminModule'
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
