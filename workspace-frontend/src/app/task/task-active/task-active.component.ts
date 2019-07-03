import { Component, OnInit } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Configs } from '../../config';
import { TaskApiService } from '../../shared/services/task-api.service';
import { TaskDataService } from '../../shared/services/task-data.service';
import { TaskSandbox } from '../task.sandbox';

@Component({
  selector: 'app-task-active',
  templateUrl: './task-active.component.html',
  styleUrls: ['./task-active.component.scss']
})
export class TaskActiveComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;

  constructor(
    private taskApiService:TaskApiService,
    public taskDataService:TaskDataService,
    private taskSandbox:TaskSandbox
  ) { }


  ngOnInit() {
    this.taskDataService.getTasks.selectedTab = 'activeTasks'
    this.taskDataService.getTasks.page = 1 ;
    this.taskSandbox.getTaskList();
  }

  ngOnDestroy(){
    this.taskDataService
    this.taskDataService.resetTaskRunManagement();
  }

}
