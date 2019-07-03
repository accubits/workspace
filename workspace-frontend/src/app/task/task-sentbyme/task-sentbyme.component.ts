import { Component, OnInit } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Configs } from '../../config';
import { TaskApiService } from '../../shared/services/task-api.service';
import { TaskDataService } from '../../shared/services/task-data.service';
import { TaskSandbox } from '../task.sandbox';
import { TaskUtilityService } from '../../shared/services/task-utility.service';


@Component({
  selector: 'app-task-sentbyme',
  templateUrl: './task-sentbyme.component.html',
  styleUrls: ['./task-sentbyme.component.scss']
})
export class TaskSentbymeComponent implements OnInit {

  public assetImageLocation =  Configs.assetImageURl;

  constructor(
    private taskApiService:TaskApiService,
    public taskDataService:TaskDataService,
    private taskUtilityService:TaskUtilityService,
    private taskSandbox:TaskSandbox
  ) { }

  public assetUrl = Configs.assetBaseUrl;
  ngOnInit() {
    this.taskDataService.getTasks.selectedTab = 'setByMe'
    this.taskSandbox.getTaskList();
  }

 /* Checking all tasks[Start] */ 
  checkAllTask($event):void{
    for(let i=0; i<this.taskDataService.getTasks.taskList.length;i++){
        this.taskDataService.getTasks.taskList[i].selected =  this.taskDataService.taskRunManagement.selectedAll;
        this.taskUtilityService.manageTaskSelection(this.taskDataService.getTasks.taskList[i].selected,i)
    }
  }

  /* Checking all tasks[Start] */

  /* Sorting tasks base on coumns[Start] */
  sortTasks(sortItem:string){
    this.taskDataService.getTasks.selectedSortItem = sortItem;
    this.taskDataService.getTasks.sortOrder === 'asc'?this.taskDataService.getTasks.sortOrder = 'desc':this.taskDataService.getTasks.sortOrder = 'asc';
    this.taskSandbox.getTaskList();

  }
  /* Sorting tasks base on coumns[End] */
  ngOnDestroy()
  {
    this.taskDataService.resetGetTask();
    this.taskDataService.resetTaskRunManagement();
  }
}




