import { Component, OnInit } from '@angular/core';
import { TimeDateService } from '../../shared/services/time-date.service';
import {Configs} from '../../config';
import {HttpHeaders, HttpClient} from '@angular/common/http';
import {Routes, RouterModule, Router, ActivatedRoute, Params} from '@angular/router';
import { AuthenticationService} from '../../shared/services/authentication.service';

@Component({
  selector: 'app-reset-password',
  templateUrl: './reset-password.component.html',
  styleUrls: ['./reset-password.component.scss']
})
export class ResetPasswordComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  date: any;
  newPassword: string;
  confirmPassword:string;
  error: string;
  success: string;
  tokenKey:string;
  greet:string;

  constructor(private timeDateService: TimeDateService, private http:HttpClient, private activatedRoute: ActivatedRoute,  private router: Router) {
    this.activatedRoute.params.forEach((params: Params) => {
       this.tokenKey = params['token'];
      //console.log(this.tokenKey); // Print the parameter to the console.
    });
  }
  ngOnInit() {
    this.date = this.timeDateService.getDate();
    this.error = '';
    this.newPassword = '';
    this.confirmPassword = '';
    this.success = '';
  }
  resetPassword()
  {
    this.error = '';
    if (this.newPassword === '' && this.confirmPassword === '') {
      this.error = 'Please fill the fields !';
      return;
    }
    var strongRegex = /^.*(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!@#$%^&*()_+-=:;<,.?>]).*$/;
    if (!strongRegex.test(this.newPassword)){
      this.error = 'Password must contain at least a number, alphabet and a special character';
      return;
    }
    if (this.confirmPassword !== '' && (this.confirmPassword != this.newPassword))
    {
      this.error = 'Password does not match';
      return;
    }
    const URL = Configs.api + 'password-reset-link';
    const data = {
      "token": this.tokenKey,
      "new_password":  this.newPassword,
      "new_password_confirmation": this.confirmPassword
    }//'new_password=' + this.newPassword + '&&' + 'new_password_confirmation=' + this.newPassword;
    const header = new HttpHeaders().set('Content-Type', 'application/json');
    const headers = {headers: header};
    this.http.post(URL, data, headers)
        .subscribe((result: any) => {
              //this.spinnerService.hide();
              console.log(result);
              this.success = 'Password changed sucessfully';
              this.newPassword = '';
              this.confirmPassword = '';
              this.router.navigate(['']);
            },
            err => {
              //alert(err);
              //this.error = 'Password does not match';
              //this.spinnerService.hide();
              this.newPassword = '';
              this.confirmPassword = '';
              this.error = err.statusText;
            });
  }
}
