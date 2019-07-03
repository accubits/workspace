import { Component, OnInit } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Configs } from '../../config';
import { TaskApiService } from '../../shared/services/task-api.service';
import { TaskDataService } from '../../shared/services/task-data.service';
import { TaskSandbox } from '../task.sandbox';


@Component({
  selector: 'app-task-archived',
  templateUrl: './task-archived.component.html',
  styleUrls: ['./task-archived.component.scss']
})
export class TaskArchivedComponent implements OnInit {

  public assetImageLocation =  Configs.assetImageURl;

  constructor(
    private taskApiService:TaskApiService,
    public taskDataService:TaskDataService,
    private taskSandbox:TaskSandbox
  ) { }

  public assetUrl = Configs.assetBaseUrl;
  ngOnInit() {
    this.taskDataService.getTasks.selectedTab = 'archive'
    this.taskSandbox.getTaskList();
    this.taskSandbox.getSubtasksforselTask();
  }

  ngOnDestroy() {
    this.taskDataService.resetGetTask();
    this.taskDataService.resetTaskRunManagement();
  }

}
