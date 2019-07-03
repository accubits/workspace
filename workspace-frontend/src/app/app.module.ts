import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { Ng4LoadingSpinnerModule } from 'ng4-loading-spinner';
import { AppRoutingModule } from './app-routing.module';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { AppComponent } from './app.component';
import { CookieService } from 'ngx-cookie-service';
import { SharedModule } from './shared/shared.module';
import { AuthenticationService } from './shared/services/authentication.service';
import { AuthGuardGuard } from './shared/services/auth-guard.guard';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HostListener } from '@angular/core';
import { ReactiveFormsModule } from '@angular/forms';
import { UnaAuthorizedInterceptor } from './auth-middlwares/interceptors/http-unauthorized.interceptor';
import { FormsModule } from './forms/forms.module';
import { DndListModule } from 'ngx-drag-and-drop-lists';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { PERFECT_SCROLLBAR_CONFIG } from 'ngx-perfect-scrollbar';
import { PerfectScrollbarConfigInterface } from 'ngx-perfect-scrollbar';
import { CKEditorModule } from 'ngx-ckeditor';
import { TimeAndReportsModule } from './time-and-reports/time-and-reports.module';



// Perfect Scrollbar
const DEFAULT_PERFECT_SCROLLBAR_CONFIG: PerfectScrollbarConfigInterface = {
  suppressScrollX: false,
  suppressScrollY: false

};
// Perfect Scrollbar

@NgModule({
  declarations: [
    AppComponent,

  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    SharedModule,
    HttpClientModule,
    BrowserAnimationsModule,
    ReactiveFormsModule,
    Ng4LoadingSpinnerModule.forRoot(),
    //FormsModule,
    DndListModule,
    PerfectScrollbarModule,
    CKEditorModule,
    //TimeAndReportsModule
    ],
  providers: [
    CookieService,
    AuthenticationService,
    AuthGuardGuard,
    { provide: HTTP_INTERCEPTORS, useClass: UnaAuthorizedInterceptor, multi: true },
    {
      provide: PERFECT_SCROLLBAR_CONFIG,
      useValue: DEFAULT_PERFECT_SCROLLBAR_CONFIG
    }
  ],


  bootstrap: [AppComponent]
})


export class AppModule { }

