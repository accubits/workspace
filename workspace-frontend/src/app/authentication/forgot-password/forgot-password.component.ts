import { Component, OnInit } from '@angular/core';
import { TimeDateService } from '../../shared/services/time-date.service';
import {Configs} from '../../config';
import {HttpHeaders, HttpClient} from '@angular/common/http';
import {Routes, RouterModule, Router} from '@angular/router';
import { AuthenticationService} from '../../shared/services/authentication.service';
//import {HttpClient} from "../../../../node_modules/@types/selenium-webdriver/http";

@Component({
  selector: 'app-forgot-password',
  templateUrl: './forgot-password.component.html',
  styleUrls: ['./forgot-password.component.scss']
})
export class ForgotPasswordComponent implements OnInit {
    public assetUrl = Configs.assetBaseUrl;
    date: any;
    email: string;
    error: string;
    success: string;

  constructor(private timeDateService: TimeDateService, private http:HttpClient ) { }

  ngOnInit() {
    this.date = this.timeDateService.getDate();
    this.error = '';
    this.email = '';
    this.success = '';
  }
    forgotPassword()
  {
      console.log(this.email);
      this.error = '';
      this.success = '';
      if (this.email === '') {
          this.error = 'Please enter email address !';
          return;
      }
      const EMAIL_REGEXP = /^[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/;
      if (this.email !== '' && !EMAIL_REGEXP.test(this.email)) {
          this.error = 'Please enter a valid email address';
          return;
      }
      // this.spinnerService.show();
      const URL = Configs.api + 'forgot-password';
      const data = 'email=' + this.email;
      const header = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
      const headers = {headers: header};
      this.http.post(URL, data, headers)
          .subscribe((result: any) => {
                  console.log(result);
                  this.success = 'Please check your mail';
                  this.email = '';
              },
              err => {
                  this.email = '';
                  this.error = err.error.error.msg;
              });
  }

}
