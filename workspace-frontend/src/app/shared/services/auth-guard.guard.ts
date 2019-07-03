import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs/Observable';
import { AuthenticationService } from './authentication.service';
import {Routes, RouterModule, Router} from '@angular/router';


@Injectable()
export class AuthGuardGuard implements CanActivate {
  constructor (private authService: AuthenticationService, private router: Router) {}
  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
      if (this.authService.getUserLoggedIn() ===  false) {
           this.router.navigate([''],{ queryParams: { returnUrl: state.url }});
      }
      return this.authService.getUserLoggedIn();
  }
}
