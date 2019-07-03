import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from '../shared/shared.module';
import { TaskRoutingModule } from './task-routing.module';
import { TaskComponent } from './task.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { TaskNavbarComponent } from './task-navbar/task-navbar.component';
import { TaskOverviewComponent } from './task-overview/task-overview.component';
import { TaskActiveComponent } from './task-active/task-active.component';
import { CreateTaskpopComponent } from './create-taskpop/create-taskpop.component';
import { TaskListingComponent } from './task-listing/task-listing.component';
import { TaskSandbox } from './task.sandbox';
import { TaskSentbymeComponent } from './task-sentbyme/task-sentbyme.component';
import { TaskResponsibleComponent } from './task-responsible/task-responsible.component';
import { TaskDetailpopupComponent } from './task-detailpopup/task-detailpopup.component';
import { TaskFavouritesComponent } from './task-favourites/task-favourites.component';
import { TaskHighpriorityComponent } from './task-highpriority/task-highpriority.component';
import { TaskCompletedComponent } from './task-completed/task-completed.component';
import { TaskAllComponent } from './task-all/task-all.component';
import { TaskFilterComponent } from './task-filter/task-filter.component';
import { TaskListHeaderComponent } from './task-list-header/task-list-header.component';
import { TaskEditpopComponent } from './task-editpop/task-editpop.component';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import { CKEditorModule } from 'ngx-ckeditor';
import { TaskArchivedComponent } from './task-archived/task-archived.component';



@NgModule({
  imports: [
    CommonModule,
    SharedModule,
    TaskRoutingModule,
    FormsModule,
    ReactiveFormsModule,
    PerfectScrollbarModule,
    CKEditorModule
  ],
  providers: [TaskSandbox],

  declarations: [TaskComponent, TaskNavbarComponent, TaskOverviewComponent, TaskActiveComponent, CreateTaskpopComponent, TaskListingComponent, TaskSentbymeComponent, TaskResponsibleComponent, TaskFavouritesComponent, TaskHighpriorityComponent, TaskCompletedComponent, TaskAllComponent, TaskDetailpopupComponent, TaskFilterComponent, TaskListHeaderComponent, TaskEditpopComponent, TaskArchivedComponent]

})
export class TaskModule { }
