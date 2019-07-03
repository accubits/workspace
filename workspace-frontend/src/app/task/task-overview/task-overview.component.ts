import { Component, OnInit } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Configs } from '../../config';
import { TaskApiService } from '../../shared/services/task-api.service';
import { TaskDataService } from '../../shared/services/task-data.service';
import { TaskSandbox } from '../task.sandbox';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-task-overview',
  templateUrl: './task-overview.component.html',
  styleUrls: ['./task-overview.component.scss']
})
export class TaskOverviewComponent implements OnInit {
  tasks: any = [];
  taskOverview = {};
  today = new Date();
  constructor(
    private taskApiService: TaskApiService,
    public taskDataService: TaskDataService,
    private taskSandbox: TaskSandbox,
    private router: Router,
    private route: ActivatedRoute,
  ) { }

  public assetUrl = Configs.assetBaseUrl;

  ngOnInit() {
    this.taskDataService.getTasks.selectedTab = 'overview'
    this.taskSandbox.getTaskList();
  }
  selectTask(event) {
    event.stopPropagation();
  }

  /* Calculate checklist percentage */
  calculatePloat(index): any {
    // setTimeout(function(){
    let checked = this.taskDataService.getTasks.overviewTaskList[index].checklist.checked;
    let total = this.taskDataService.getTasks.overviewTaskList[index].checklist.total;
    let diameter = Math.round(629 * (((checked / total) * 100) / 100));
    return diameter;
  }

  /*Partial Updation(making a selected task fav) */
  makeFavourite(fav, slug, pri): void {
    event.stopPropagation();
    this.taskDataService.taskPartialUpdates.favourite = fav;
    this.taskDataService.taskPartialUpdates.task_slug = slug;
    pri === 1 ? this.taskDataService.taskPartialUpdates.priority = true : this.taskDataService.taskPartialUpdates.priority = false
    this.taskSandbox.partialUpdateTask();
  }

  makePriority(pri, slug, fav): void {
    event.stopPropagation();
    this.taskDataService.taskPartialUpdates.priority = pri;
    this.taskDataService.taskPartialUpdates.task_slug = slug;
    fav === 1 ? this.taskDataService.taskPartialUpdates.favourite = true : this.taskDataService.taskPartialUpdates.favourite = false
    this.taskSandbox.partialUpdateTask();
  }
  /* View Task Details */
  viewTaskDetails(idx): void {
    //alert("dfsf");
    this.taskDataService.taskDetails.selectedTask = this.taskDataService.getTasks.overviewTaskList[idx].slug;
    this.router.navigate([{outlets:{detailpopup:['task-detail',this.taskDataService.taskDetails.selectedTask]}}], {
      relativeTo: this.route.parent // <--- PARENT activated route.
  }); 
  }
  makeComplete(status, task, index): void {
    // event.stopPropagation();
    this.taskDataService.taskRunManagement.selectedTaskIndex = index;
    this.taskDataService.taskRunManagement.selectedTaskIds.push({
      slug: task.slug
    });
    //this.taskDataService.taskRunManagement.status = 'complete'
    // this.taskSandbox.manageTaskStatus();
     this.taskDataService.completePopup.show = true
  }

  ngOnDestroy()
  {
    this.taskDataService.resetGetTask();
    this.taskDataService.resetTaskRunManagement();
  }
}
