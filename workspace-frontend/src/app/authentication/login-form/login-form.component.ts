import { Component, OnInit } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { TimeDateService } from '../../shared/services/time-date.service';
import { Configs } from '../../config';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Routes, ActivatedRoute, RouterModule, Router } from '@angular/router';
import { AuthenticationService } from '../../shared/services/authentication.service';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';





@Component({
    selector: 'app-login-form',
    templateUrl: './login-form.component.html',
    styleUrls: ['./login-form.component.scss']
})
export class LoginFormComponent implements OnInit {
    loginProgress = false;
    public assetUrl = Configs.assetBaseUrl;
    forgot = true;
    date: any;
    greet: string;
    email: string;
    password: string;
    error: string;
    isPartner ='false';
    emailError = false;
    passError = false;
    returnUrl = '';
    constructor(private cookieService: CookieService,
        private timeDateService: TimeDateService,
        private router: Router,
        private route: ActivatedRoute,
        public spinner: Ng4LoadingSpinnerService,
        private http: HttpClient,
        private authenticate: AuthenticationService) {
        this.email = '';
        this.password = '';
        this.error = '';
    }

    ngOnInit() {
        this.date = this.timeDateService.getDate();
        this.greet = this.timeDateService.getGreeting();
        this.error = '';
    }
    login() {
     //   if (this.loginProgress) return;
        this.loginProgress = true;
        this.emailError = false;
        this.passError = false;
        this.error = '';
        if (this.email === '' && this.password === '') {
            this.emailError = true;
            this.passError = true;
            this.error = 'Please enter your login credentials!';
            return;
        };
        if (this.email === '') {
            this.emailError = true;
            this.error = 'Please enter your email address';
            return;
        };
        const EMAIL_REGEXP = /^[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/;
        if (this.email !== '' && !EMAIL_REGEXP.test(this.email)) {
            this.emailError = true;
            this.error = 'Please enter a valid email address';
            return;
        };
        if (this.password === '') {
            this.passError = true;
            this.error = 'Please enter your password';
            return;
        };
        // this.spinnerService.show();
        const URL = Configs.api + 'login';
        const data = 'email=' + this.email + '&&' + 'password=' + this.password;
        const header = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
        const headers = { headers: header };
        this.spinner.show();
        this.http.post(URL, data, headers)
            .subscribe((result: any) => {
                // this.spinnerService.hide();
                this.loginProgress = false;
                
               this.cookieService.deleteAll();
                // this.cookieService.deleteAll('../');

                this.cookieService.set('token', result.data.token);
                this.cookieService.set('UserDetails', result.data);
                this.cookieService.set('userName', result.data.name);
                this.cookieService.set('userSlug', result.data.user_slug);
                this.cookieService.set('isDepartmentHead', (result.data.isDepartmentHead).toString());
                this.cookieService.set('isLoggedIn', 'true');
                this.authenticate.setUserLoggedIn();
                // this.router.navigate(['/authorized']);

                if (result.data.roles.indexOf('PARTNER_MANAGER') !== -1) {
                    this.cookieService.set('isPartner','true');
                    this.router.navigate(['/partner-manager']);
                }

                else if (result.data.roles.indexOf('PARTNER') !== -1) {
                    this.cookieService.set('partnerSlug', result.data.partnerSlug);
                   // this.cookieService.set('isPartner','false');
                    this.router.navigate(['/partner']);
                }
                else if (result.data.roles.indexOf('ORG_ADMIN') !== -1){
                    this.cookieService.set('orgSlug', result.data.org_slug[0]);
                    this.cookieService.set('role',result.data.roles[result.data.roles.indexOf('ORG_ADMIN')])
                    this.router.navigate(['/authorized']);

                }

                else if (result.data.roles.indexOf('SUPER_ADMIN') !== -1){
                    //this.cookieService.set('orgSlug', result.data.org_slug[0]);
                    //this.cookieService.set('role',result.data.roles[result.data.roles.indexOf('ORG_ADMIN')])
                    this.router.navigate(['/super-admin']);

                }
                else {
                    this.cookieService.set('orgSlug', result.data.org_slug[0]);
                    this.cookieService.set('role',result.data.roles[0])

                    // this.returnUrl = this.route.snapshot.queryParams['returnUrl']
                    this.router.navigate(['/authorized']);
                }
                this.spinner.hide();

            },
                err => {
                    console.log(err);
                    this.spinner.hide();
                    //this.spinnerService.hide();
                    this.loginProgress = false;
                    this.error = err.error.error;
                });
    }
}
