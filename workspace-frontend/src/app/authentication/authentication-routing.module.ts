import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {  AuthenticationComponent} from './authentication.component';
import { ForgotPasswordComponent } from './forgot-password/forgot-password.component';
import { LoginFormComponent } from './login-form/login-form.component';
import { ResetPasswordComponent } from './reset-password/reset-password.component';
import {RegisterFormComponent } from './register-form/register-form.component';

const routes: Routes = [
  {
    path: '',
    component: AuthenticationComponent,
    children: [
      {
        path: '',
        component: LoginFormComponent
      },
      {
        path: 'forgot',
        component: ForgotPasswordComponent
      },
      {
        path: 'reset/:token',
        component: ResetPasswordComponent
      },
      {
        path: 'invite/:token',
        component: RegisterFormComponent
      }
    ]
  }
 ];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AuthenticationRoutingModule { }
