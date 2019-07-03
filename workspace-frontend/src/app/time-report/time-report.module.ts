import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { SharedModule } from '../shared/shared.module';
import { TimeReportRoutingModule } from './time-report-routing.module';
import { TimeReportComponent } from './time-report.component';
import { TimeReportNavComponent } from './time-report-nav/time-report-nav.component';
import { WorkTimeComponent } from './work-time/work-time.component';
import { WorkReportComponent } from './work-report/work-report.component';
import { WorkReportFilterComponent } from './work-report-filter/work-report-filter.component';
import { WorkTimeDetailsComponent } from './work-time-details/work-time-details.component';
import { WorkReportDetailsComponent } from './work-report-details/work-report-details.component';
import { TimeReportSandbox } from './time-report.sandbox';
import { WorkTimeFilterComponent } from './work-time-filter/work-time-filter.component';
import { WorkReportAddComponent } from './work-report-add/work-report-add.component';
import { CreateReportComponent } from './create-report/create-report.component';
import { WorkTimePopComponent } from './work-time-pop/work-time-pop.component';

@NgModule({
  imports: [
    CommonModule,
    TimeReportRoutingModule,
    SharedModule,
    PerfectScrollbarModule 
  ],
  exports:[
    CreateReportComponent
  ],

  providers:[TimeReportSandbox],
  declarations: [TimeReportComponent, TimeReportNavComponent, WorkTimeComponent, WorkReportComponent, WorkReportFilterComponent, WorkTimeDetailsComponent, WorkReportDetailsComponent, WorkTimeFilterComponent, WorkReportAddComponent, CreateReportComponent, WorkTimePopComponent]
})
export class TimeReportModule { }
