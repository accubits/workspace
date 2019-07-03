import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { TaskComponent } from './task.component';
import { TaskOverviewComponent } from './task-overview/task-overview.component';
import { TaskActiveComponent } from './task-active/task-active.component';
import { TaskSentbymeComponent } from './task-sentbyme/task-sentbyme.component';
import { TaskResponsibleComponent } from './task-responsible/task-responsible.component';
import { TaskFavouritesComponent } from './task-favourites/task-favourites.component';
import { TaskHighpriorityComponent } from './task-highpriority/task-highpriority.component';
import { TaskCompletedComponent } from './task-completed/task-completed.component';
import { TaskAllComponent } from './task-all/task-all.component';
import { TaskArchivedComponent } from './task-archived/task-archived.component';
import { TaskDetailpopupComponent } from './task-detailpopup/task-detailpopup.component';
import { CreateTaskpopComponent } from './create-taskpop/create-taskpop.component';



const routes: Routes = [
  {
    path: 'task-d',
    component: TaskComponent,
    children: [
      { path: '', pathMatch: 'full', redirectTo: 'task-overview'},
      { path: 'task-overview', component: TaskOverviewComponent },
      { path: 'task-active', component: TaskActiveComponent },
      { path: 'task-setbyme', component: TaskSentbymeComponent },
      { path: 'task-responsible', component: TaskResponsibleComponent },
      { path: 'task-favourites', component: TaskFavouritesComponent },
      { path: 'task-highpriority', component: TaskHighpriorityComponent },
      { path: 'task-completed', component: TaskCompletedComponent },
      { path: 'task-all', component: TaskAllComponent },
      { path: 'task-archive', component: TaskArchivedComponent },
      // { path: 'task-detail', component: TaskDetailpopupComponent },
      { path: 'task-create', component: CreateTaskpopComponent },  
      { path: 'task-detail/:selectedTaskslug', component: TaskDetailpopupComponent,outlet: 'detailpopup',
     
    
    },  
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TaskRoutingModule { }

