import { Component, OnInit } from '@angular/core';
import { TaskDataService } from '../../shared/services/task-data.service';
import { TaskUtilityService } from '../../shared/services/task-utility.service';
import { TaskSandbox } from '../task.sandbox';

@Component({
  selector: 'app-task-list-header',
  templateUrl: './task-list-header.component.html',
  styleUrls: ['./task-list-header.component.scss']
})
export class TaskListHeaderComponent implements OnInit {

  isBtnActive:boolean = false;
  
  constructor(
    public taskDataService:TaskDataService,
    private taskUtilityService:TaskUtilityService,
    private taskSandbox:TaskSandbox
  ) { }

  ngOnInit() {
  }

  /* Sorting tasks base on coumns[Start] */
  sortTasks(sortItem:string){
    this.taskDataService.getTasks.selectedSortItem = sortItem;
    this.taskDataService.getTasks.sortOrder === 'asc'?this.taskDataService.getTasks.sortOrder = 'desc':this.taskDataService.getTasks.sortOrder = 'asc';
    this.taskSandbox.getTaskList();

  }
  /* Sorting tasks base on coumns[End] */

  /* Checking all tasks[Start] */ 
  checkAllTask($event):void{

   for(let i=0; i<this.taskDataService.getTasks.taskList.length;i++){
        this.taskDataService.getTasks.taskList[i].selected =  this.taskDataService.taskRunManagement.selectedAll;
        this.taskUtilityService.manageTaskSelection(this.taskDataService.getTasks.taskList[i].selected,i)
    }
  }

  /* Checking all tasks[Start] */

}
