import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AuthorizedComponent } from './authorized.component';

const routes: Routes = [
  {
    path: '',
    component: AuthorizedComponent,
    children: [
      { path: '', redirectTo: 'activity', pathMatch: 'full' },

      {
        path: 'activity',
        loadChildren: 'app/activitystream/activitystream.module#ActivitystreamModule'
      },
      {
        path: 'task',
        loadChildren: 'app/task/task.module#TaskModule'
      },
      {
        path: 'forms',
        loadChildren: 'app/forms/forms.module#FormsModule'
      },
      {
        path: 'chats',
        loadChildren: 'app/chats/chats.module#ChatsModule'
      },
      {
        path: 'drive',
        loadChildren: 'app/drive/drive.module#DriveModule'
      },
      {
        path: 'timeAndReports',
        loadChildren: 'app/time-and-reports/time-and-reports.module#TimeAndReportsModule'
      },
      {
        path: 'timeReports',
        loadChildren: 'app/time-report/time-report.module#TimeReportModule'
      },
      {
        path: 'settings',
        loadChildren: 'app/settings/settings.module#SettingsModule'
      },
      {
        path: 'calendar',
        loadChildren: 'app/calendar/calendar.module#CalendarModule'
      },
      {
        path: 'hrm',
        loadChildren: 'app/hrm/hrm.module#HrmModule'
      },
      {
        path: 'crm',
        loadChildren: 'app/crm/crm.module#CrmModule'
      },
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AuthorizedRoutingModule { }
