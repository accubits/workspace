import { Component, OnInit } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Configs } from '../../config';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Observable } from "rxjs/Observable";
import { ActivatedRoute, Router } from '@angular/router';
import 'rxjs/add/observable/throw';

@Component({
  selector: 'app-register-form',
  templateUrl: './register-form.component.html',
  styleUrls: ['./register-form.component.scss']
})
export class RegisterFormComponent implements OnInit {

  token: string = "";
  userName: '';
  password: '';
  conPassword: '';
  isValidated = true;
  public href: string = "";
  constructor(private spinner: Ng4LoadingSpinnerService,
    private router: Router,
    private http: HttpClient) { }

  ngOnInit() {
    this.href = this.router.url;
    this.token = this.href.split("/")[3];
  }

    /* Validating creating new message[Start] */
    validateVerifyEmail(): boolean {
      this.isValidated = true;
      if (!this.isValidated && !this.userName) this.isValidated = false;
      if (!this.isValidated && !this.password) this.isValidated = false;
      if (!this.isValidated && !this.conPassword) this.isValidated = false;
      return this.isValidated;
    }
    /* Validating creating new message[end] */

  verifyUser() {
    console.log('dsad', this.router.navigateByUrl)
    if (!this.validateVerifyEmail()) return;
    const URL = Configs.api + 'verifyAndChangePassword';
    let data = {
      "token": this.token,
      "name": this.userName,
      "password": this.password,
      "password_confirmation": this.conPassword,
    }
    const header = new HttpHeaders().set('Content-Type', 'application/json');
    const headers = { headers: header };
    this.http.post(URL, data, headers)
            .subscribe((result: any) => {  
            this.router.navigate(['']);
           },   
           err => {
            console.log(err);
            this.spinner.hide();
        });    
}
}
