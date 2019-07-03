import { Injectable } from '@angular/core';
import { TaskDataService } from './task-data.service';

@Injectable()
export class TaskUtilityService {

  constructor(
    public taskDataService: TaskDataService
  ) { }

  /* Managing task selction[Start] */
  manageTaskSelection(isSelcted:boolean,index: number): void {
    // Checking the task is selected
    if (isSelcted) {
      this.taskDataService.taskRunManagement.selectedTaskIds.push(this.taskDataService.getTasks.taskList[index].slug);  // Inserting in to slected task list
    } else {
      let idx = this.taskDataService.taskRunManagement.selectedTaskIds.indexOf(this.taskDataService.getTasks.taskList[index].slug);
      this.taskDataService.taskRunManagement.selectedTaskIds.splice(idx, 1); // Deleting fron slected task list
    }
    if(this.taskDataService.taskRunManagement.selectedTaskIds.length > 0) {
      this.taskDataService.taskRunManagement.showPopup = true;
    }else{
      this.taskDataService.taskRunManagement.showPopup = false;
      this.taskDataService.taskRunManagement.selectedAll = false;
    } 
  }
  /* Managing task selction[End] */
}
