import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
//
import { AuthorizedRoutingModule } from './authorized-routing.module';
import { AuthorizedComponent } from './authorized.component';
import { HeaderComponent } from './header/header.component';
import { FooterComponent } from './footer/footer.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { SharedModule } from '../shared/shared.module';
import { TimeReportModule } from '../time-report/time-report.module'
import { WorkHoursPauseComponent } from './header/work-hours-pause/work-hours-pause.component';
import { WorkHoursContinueComponent } from './header/work-hours-continue/work-hours-continue.component';
import { WorkHoursResumeComponent } from './header/work-hours-resume/work-hours-resume.component';
import { EditWorkComponent } from './header/edit-work/edit-work.component';
import { PreviousDayComponent } from './header/previous-day/previous-day.component';
import { WorkHoursStartComponent } from './header/work-hours-start/work-hours-start.component';
import { WorkReportComponent } from './header/work-report/work-report.component';
import { AuthorizedSandbox } from './authorized.sandbox';

// import {CreateReportComponent} from './../time-report/create-report/create-report.component'
// import { TimeReportSandbox} from './../time-report/time-report.sandbox'

@NgModule({
  imports: [
    CommonModule,
    SharedModule,
    AuthorizedRoutingModule,
    PerfectScrollbarModule,
    TimeReportModule
    
  ],
  declarations: [AuthorizedComponent, HeaderComponent, FooterComponent, SidebarComponent, WorkHoursPauseComponent,WorkHoursContinueComponent, WorkHoursResumeComponent, EditWorkComponent, PreviousDayComponent, WorkHoursStartComponent, WorkReportComponent],
  providers:[AuthorizedSandbox],
})
export class AuthorizedModule {}