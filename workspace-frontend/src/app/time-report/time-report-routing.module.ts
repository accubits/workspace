import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { TimeReportComponent } from './time-report.component';
import { TimeReportNavComponent } from './time-report-nav/time-report-nav.component';
import { WorkReportComponent } from './work-report/work-report.component';
import { WorkTimeComponent } from './work-time/work-time.component';

const routes: Routes = [
  {
    path: '',
    component: TimeReportComponent,
    children: [
      { path:'',redirectTo: 'workTime', pathMatch: 'full' },
      { path :'workTime', component:WorkTimeComponent },
      { path :'workReport', component:WorkReportComponent }

    ]
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TimeReportRoutingModule { }
