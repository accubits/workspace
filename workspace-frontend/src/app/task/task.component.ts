import { Component, OnInit, NgModule, HostListener, Inject } from '@angular/core';
import { Location } from '@angular/common';
import { Subject } from 'rxjs/Subject';
import 'rxjs/add/operator/debounceTime';
import 'rxjs/add/operator/distinctUntilChanged';
import 'rxjs/add/operator/switchMap';
import { DOCUMENT } from '@angular/platform-browser';
import { MenuToggle } from '../shared/shared-logics';
import { FormControl } from '@angular/forms';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Routes, RouterModule, Router, ActivatedRoute } from '@angular/router';
import { PopupOverlayComponent } from '../shared/popup-overlay/popup-overlay.component';
import { TaskDataService } from '../shared/services/task-data.service';
import { TaskSandbox } from './task.sandbox';
import { CookieService } from 'ngx-cookie-service';
import { Configs } from '../config';

@Component({
  selector: 'app-task',
  templateUrl: './task.component.html',
  styleUrls: ['./task.component.scss']
})
export class TaskComponent implements OnInit {
  showSearch: boolean = false;
  searchDebounce$ = new Subject<string>();
  priorityOrfavPopup = false;
  priorityOrfavPopupText = '';

  constructor(
    private cookieService: CookieService,
    public taskDataService: TaskDataService,
    private taskSandbox: TaskSandbox,
    private router: Router,
    private route: ActivatedRoute,
    private location: Location

  ) {

    this.searchDebounce$
      .debounceTime(800)
      .distinctUntilChanged()
      .subscribe(value => this.taskSandbox.getTaskList()

      );

    this.router.events.subscribe((url: any) => {
      if (this.router.url.indexOf('task-detail') === -1) {
        taskDataService.detailPopup.show = false;
      }
    });


  }

  myControl: FormControl = new FormControl();
  data: {};


  public assetUrl = Configs.assetBaseUrl;
  ngOnInit() {
    // this.closeDetailPop()
  }

  closeOverlay(){
    this.taskDataService.archivePopup.show = false;
    this.taskDataService.deletePopup.show = false;
  }

  searchTasks(value): void {
    this.searchDebounce$.next(value);

  }

  // Accesing sanbox layer for API call
  manageTaskStatus(selStatus: string): void {
    this.taskDataService.taskRunManagement.status = selStatus;
    this.taskSandbox.manageTaskStatus();
  }

  resetFilter(): void {
    this.taskDataService.getTasks.isFilterdBy = false;
    this.taskSandbox.getTaskList();
    this.taskDataService.resetFilter();
  }

  deleteTask(): void {
    this.taskSandbox.deleteTask();
  }

  archiveTask(): void {
    this.taskSandbox.archiveTask();
  }

  deleteBulkTask(): void {
    this.taskSandbox.deleteBulkTasks();
  }
 
  confirmPriorityOrfav(): void {
    this.taskDataService.taskBulkPriorityOrFav.taskSlugs = this.taskDataService.taskRunManagement.selectedTaskIds;
    if (this.taskDataService.taskBulkPriorityOrFav.key === 'favourite') {
      let taskException = this.taskDataService.getTasks.taskList.filter(
        task => task.selected && task.favourite === 0)[0];
      if (taskException) {
        this.taskDataService.taskBulkPriorityOrFav.value = true;
      } else {
        this.taskDataService.taskBulkPriorityOrFav.value = false;
      }

    } else {
      let taskException = this.taskDataService.getTasks.taskList.filter(
        task => task.selected && task.priority === 0);
      if (taskException) {
        this.taskDataService.taskBulkPriorityOrFav.value = true;
      } else {
        this.taskDataService.taskBulkPriorityOrFav.value = false;
      }
    }


    this.taskSandbox.confirmPriorityOrfav();
  }
  completeTask(): void {
    this.taskDataService.taskRunManagement.status = 'complete'
    this.taskSandbox.manageTaskStatus();
    this.taskDataService.completePopup.show = false;
  }
  cancelCompletion(): void {
    this.taskSandbox.getTaskList();
    this.taskDataService.completePopup.show = false;
  }

  removeFilterList(filters): void {

  }

 
  // // Navigate to create
  // closeCreatePopup()
  // {
  //   console.log(this.router.url);
  //   if(this.router.url === '/authorized/task/task-create')
  //   {
  //     this.router.navigate(['authorized/activity/as_recent']);
  //   }


  //  pageChanged($event):void{
  //     this.taskDataService.getTasks.page = $event;
  //     this.taskSandbox.getTaskList();
  //   }

  ngOnDestroy() {
    this.taskDataService.resetGetTask();
    this.resetFilter();
  }

  /* Delete Task */
  showDeleteTask(): void {
    this.taskDataService.deleteBulkPopup.show = true
  }

  /*PrioriyOrfav */
  showPriorityOrfav(selected): void {
    if (!selected) return;
    this.taskDataService.taskBulkPriorityOrFav.key = selected;
    selected === 'priority' ?
      this.priorityOrfavPopupText = 'Are you sure you want to make this tasks as high priority?' :
      this.priorityOrfavPopupText = 'Are you sure you want to make this tasks as favourite?'
    this.taskDataService.priorityorfav.show = true;
  }

  closeDetailPop(): void {

    this.location.back();
    this.taskDataService.resetSelectedTaskDetails();
  }

  closeEditDetailPop(): void {
    this.taskDataService.taskDetails.showEditTaskPop = false;
  }

}

