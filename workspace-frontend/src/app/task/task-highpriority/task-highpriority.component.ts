import { Component, OnInit } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Configs } from '../../config';
import { TaskApiService } from '../../shared/services/task-api.service';
import { TaskDataService } from '../../shared/services/task-data.service';
import { TaskSandbox } from '../task.sandbox';

@Component({
  selector: 'app-task-highpriority',
  templateUrl: './task-highpriority.component.html',
  styleUrls: ['./task-highpriority.component.scss']
})
export class TaskHighpriorityComponent implements OnInit {

  public assetImageLocation =  Configs.assetImageURl;

  constructor(
    private taskApiService:TaskApiService,
    public taskDataService:TaskDataService,
    private taskSandbox:TaskSandbox
  ) { }

  public assetUrl = Configs.assetBaseUrl;
  ngOnInit() {
    this.taskDataService.getTasks.selectedTab = 'highPriority'
    this.taskSandbox.getTaskList();
  }

  ngOnDestroy(){
    this.taskDataService.resetTaskRunManagement();
  }

}
