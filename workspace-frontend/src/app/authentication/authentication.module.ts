import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';

import { AuthenticationRoutingModule } from './authentication-routing.module';
import { ForgotPasswordComponent } from './forgot-password/forgot-password.component';
import { LoginFormComponent } from './login-form/login-form.component';
import { FormsModule } from '@angular/forms';
import { ResetPasswordComponent } from './reset-password/reset-password.component';
import { AuthenticationComponent } from './authentication.component';
import { RegisterFormComponent } from './register-form/register-form.component';

@NgModule({
  imports: [
    CommonModule,
    HttpClientModule,
    AuthenticationRoutingModule,
    FormsModule,
   ],
  declarations: [ForgotPasswordComponent, LoginFormComponent, ResetPasswordComponent, AuthenticationComponent, RegisterFormComponent],
  providers: []
})
export class AuthenticationModule { }
