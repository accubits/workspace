import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { Configs } from '../../config';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Routes, Router } from '@angular/router';



@Injectable()
export class AuthenticationService {

    private isUserLoggedIn;
    constructor(private cookieService: CookieService,
        private http: HttpClient,
        private router: Router
    ) {
        this.isUserLoggedIn = false;

    }

    /* Set loggedin User[Start] */
    setUserLoggedIn() {
        if (this.cookieService.get('isLoggedIn') === 'true' && this.cookieService.get('token')) {
            this.isUserLoggedIn = true;
        } else {
            this.isUserLoggedIn = false;
        }
    }
    /* Set loggedin User[End] */

    /* Get loggedin User[Start] */
    getUserLoggedIn() {
        if (this.cookieService.get('isLoggedIn') === 'true' && this.cookieService.get('token')) {
            this.isUserLoggedIn = true;
        } else {
            this.isUserLoggedIn = false;
        }
        return this.isUserLoggedIn;
    }
    /* Get loggedin User[End] */

    /* Application logout[Start] */
    blockLogout = false;
    logout() {
        if(this.blockLogout)return;
        this.blockLogout = true;
        
        const URL = Configs.api + 'usermanagement/logout';
        const header = new HttpHeaders().set('Content-Type', 'application/json')
        const headers = { headers: header };
        this.http.post(URL, {}, headers)
          .subscribe((result: any) => {
            console.log(result);
       //    this.cookieService.deleteAll();
            this.router.navigate(['']);
            setTimeout(() => {
                this.deleteAllCookies();
              }, 300);
            
            this.blockLogout = false;
          },
            err => {
              console.log(err);
              this.router.navigate(['']);
              setTimeout(() => {
                this.deleteAllCookies();
              }, 300);
            
              //this.cookieService.deleteAll();
            //  this.cookieService.deleteAll('../')
              this.blockLogout = false;
              // this.spinnerService.hide();
            });
      }

      deleteAllCookies() {
      
        var res = document.cookie;
        var multiple = res.split(";");
        for(var i = 0; i < multiple.length; i++) {
           var key = multiple[i].split("=");
           document.cookie = key[0]+" =; expires = Thu, 01 Jan 1970 00:00:00 UTC;path=/";
        }
    }
}
