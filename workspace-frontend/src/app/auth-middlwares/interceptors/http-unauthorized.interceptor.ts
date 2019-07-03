import { Injectable } from '@angular/core';
import { HttpInterceptor, HttpRequest, HttpHandler, HttpEvent, HttpErrorResponse, HttpHeaders } from '@angular/common/http';
import { AuthenticationService } from '../../shared/services/authentication.service';
import { CookieService } from 'ngx-cookie-service';
import { Observable } from 'rxjs/Observable';
import { Router } from '@angular/router';
import 'rxjs/add/operator/do';


@Injectable()


export class UnaAuthorizedInterceptor implements HttpInterceptor {

    constructor(private router: Router,
        private authenticationService: AuthenticationService,
        private cookieService: CookieService
    ) { }

    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {

        const request = req.clone({
            headers: new HttpHeaders().append('Authorization', 'Bearer ' + this.cookieService.get('token'))
        });
        return next.handle(request).do(
            (event: any) => { },
            (error: any) => {
                if (error instanceof HttpErrorResponse) {
                    if (error.status === 401) {
                        this.authenticationService.logout();
                    }
                }
            }
        );
    }
}
