import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { TimeAndReportsComponent } from './time-and-reports.component';
import { AbsenceChartComponent } from './absence-chart/absence-chart.component';
import { WorktimeComponent } from './worktime/worktime.component';
import { WorkReportsComponent } from './work-reports/work-reports.component';



const routes: Routes = [
  {
    path: '',
    component: TimeAndReportsComponent,
    children: [
      { path: '', redirectTo: 'absence', pathMatch: 'full' },
      { path: 'absence', component: AbsenceChartComponent },
      { path: 'worktime', component: WorktimeComponent },
      { path: 'workreport', component: WorkReportsComponent },
    ]
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TimeAndReportsRoutingModule { }
