import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { TimeAndReportsRoutingModule } from './time-and-reports-routing.module';
import { TimeAndReportsComponent } from './time-and-reports.component';
import { TarWrapComponent } from './tar-wrap/tar-wrap.component';
import { TarNavComponent } from './tar-nav/tar-nav.component';
import { AbsenceChartComponent } from './absence-chart/absence-chart.component';
import { WorktimeComponent } from './worktime/worktime.component';
import { WorkReportsComponent } from './work-reports/work-reports.component';
import { AbLeftComponent } from './ab-left/ab-left.component';
import { AbRightComponent } from './ab-right/ab-right.component';
import { WtRightComponent } from './wt-right/wt-right.component';
import { WtLeftComponent } from './wt-left/wt-left.component';
import { WrLeftComponent } from './wr-left/wr-left.component';
import { WrRightComponent } from './wr-right/wr-right.component';
import { AbMonthComponent } from './ab-month/ab-month.component';
import { AbWeekComponent } from './ab-week/ab-week.component';
import { AbDayComponent } from './ab-day/ab-day.component';
import { WorktimeInfoComponent } from './worktime-info/worktime-info.component';
import { AddNewAbsenceComponent } from './add-new-absence/add-new-absence.component';
import { DrUnconfirmedComponent } from './dr-unconfirmed/dr-unconfirmed.component';
import { DrConfirmedComponent } from './dr-confirmed/dr-confirmed.component';
import { WrUnconfirmedComponent } from './wr-unconfirmed/wr-unconfirmed.component';
import { WrExcellenceComponent } from './wr-excellence/wr-excellence.component';
import { WrNewComponent } from './wr-new/wr-new.component';
import { WrYearComponent } from './wr-year/wr-year.component';
import { WrMonthComponent } from './wr-month/wr-month.component';
import { WrWeekComponent } from './wr-week/wr-week.component';
import { AbsenceDetailsComponent } from './absence-details/absence-details.component';
import { WrFilterComponent } from './wr-filter/wr-filter.component';
import { WtFilterComponent } from './wt-filter/wt-filter.component';

@NgModule({
  imports: [
    CommonModule,
    TimeAndReportsRoutingModule,
    PerfectScrollbarModule
  ],
  declarations: [TimeAndReportsComponent, TarWrapComponent, TarNavComponent, AbsenceChartComponent, WorktimeComponent, WorkReportsComponent, AbLeftComponent, AbRightComponent, WtRightComponent, WtLeftComponent, WrLeftComponent, WrRightComponent, AbMonthComponent, AbWeekComponent, AbDayComponent, WorktimeInfoComponent, AddNewAbsenceComponent, DrUnconfirmedComponent, DrConfirmedComponent, WrUnconfirmedComponent, WrExcellenceComponent, WrNewComponent, WrYearComponent, WrMonthComponent, WrWeekComponent, AbsenceDetailsComponent, WrFilterComponent, WtFilterComponent]
})
export class TimeAndReportsModule { }
