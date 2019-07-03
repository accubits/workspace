import { Component, OnInit } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Configs } from '../../config';
import { TaskApiService } from '../../shared/services/task-api.service';
import { TaskDataService } from '../../shared/services/task-data.service';
import { TaskSandbox } from '../task.sandbox';

@Component({
  selector: 'app-task-all',
  templateUrl: './task-all.component.html',
  styleUrls: ['./task-all.component.scss']
})
export class TaskAllComponent implements OnInit {

  public assetImageLocation =  Configs.assetImageURl;

  constructor(
    private taskApiService:TaskApiService,
    public taskDataService:TaskDataService,
    private taskSandbox:TaskSandbox
  ) { }

  public assetUrl = Configs.assetBaseUrl;
  ngOnInit() {
    this.taskDataService.getTasks.selectedTab = ''
    this.taskSandbox.getTaskList();
    this.taskSandbox.getSubtasksforselTask();
  }

  ngOnDestroy() {
    this.taskDataService.resetGetTask();
    this.taskDataService.resetTaskRunManagement();
  }
}
