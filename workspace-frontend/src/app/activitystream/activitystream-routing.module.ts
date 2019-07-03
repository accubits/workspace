import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ActivitystreamComponent } from './activitystream.component';
import { AsRecentComponent } from './as-recent/as-recent.component';
import { AsTopComponent } from './as-top/as-top.component';
import { AsAnnouncementComponent } from './as-announcement/as-announcement.component';
import { AsTaskComponent } from './as-task/as-task.component';
import { AsWorkflowComponent } from './as-workflow/as-workflow.component';


const routes: Routes = [
  {
    path: '',
    component: ActivitystreamComponent,
    children: [
      { path: '', pathMatch: 'full', redirectTo: 'as_recent' },
      { path: 'as_recent', component: AsRecentComponent },
      { path: 'as_top', component: AsTopComponent },
      { path: 'as_announcement', component: AsAnnouncementComponent },
      { path: 'as_task', component: AsTaskComponent },
      { path: 'as_workflow', component: AsWorkflowComponent },
    ],
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ActivitystreamRoutingModule { }
