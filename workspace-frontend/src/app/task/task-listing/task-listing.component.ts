import { Component, OnInit, Input } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { Configs } from '../../config';
import { TaskDataService } from '../../shared/services/task-data.service'
import { TaskUtilityService } from '../../shared/services/task-utility.service'
import { UtilityService } from '../../shared/services/utility.service'
import { TaskSandbox } from '../task.sandbox'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { CookieService } from 'ngx-cookie-service';


@Component({
  selector: 'app-task-listing',
  templateUrl: './task-listing.component.html',
  styleUrls: ['./task-listing.component.scss'],

  
})
export class TaskListingComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  userSlug:string;
  toggleActive:boolean=false;
  @Input('task') task;
  @Input('index') index;
  @Input('subTask') subTask;
  showSubTaskSection: boolean = false;
  subtaskIntent = 0;
  subtaskarray = [];
  en = {}

  constructor(
    public taskDataService: TaskDataService,
    public taskUtilityService: TaskUtilityService,
    public utilityService: UtilityService,
    public taskSandbox: TaskSandbox,
    private router: Router,
    private cookieService: CookieService,
    private route: ActivatedRoute,
    private spinner: Ng4LoadingSpinnerService,

  ) { }

  selectTask(userStatusButtons) {
    this.taskDataService.taskRunManagement.start = userStatusButtons.start;
    this.taskDataService.taskRunManagement.pause = userStatusButtons.pause;
    this.taskDataService.taskRunManagement.complete = userStatusButtons.complete;
    event.stopPropagation();
  }

  ngOnInit() {
    this.userSlug = this.cookieService.get('userSlug');
  }

  /* toogle sub task listing */
  toogleSubTask($event, index): void {
    event.stopPropagation();
    this.taskDataService.taskDetails.selectedTask = this.taskDataService.getTasks.taskList[index].slug;
    this.taskSandbox.getSubtasksforselTask();
    this.showSubTaskSection = !this.showSubTaskSection;
    this.showSubTaskSection? this.subtaskIntent =  2:  this.subtaskIntent =  0 ;
    this.toggleActive = !this.toggleActive;
    //this.taskDataService.getTasks.taskList[index].showSubTask = !this.taskDataService.getTasks.taskList[index].showSubTask;

  }

  // /* toogle sub task listing */
  // toogleSubTaskOfSubtask($event, index): void {
  //   event.stopPropagation();
  //   this.taskDataService.taskDetails.selectedTask = this.taskDataService.getTasks.subtasksList[index].slug;
  //   this.taskSandbox.getSubtasksforselTask();
  //   this.showSubTaskSection = !this.showSubTaskSection;
  //   this.showSubTaskSection? this.subtaskIntent =  2:  this.subtaskIntent =  0 ;
  //   //this.taskDataService.getTasks.taskList[index].showSubTask = !this.taskDataService.getTasks.taskList[index].showSubTask;

  // }


  // /* toogle sub task listing */
  // toogleSubTask($event, index): void {
  //   $event.stopPropagation();
  //   this.toggleActive = !this.toggleActive;
  //   this.taskDataService.taskDetails.selectedTask = this.taskDataService.getTasks.taskList[index].slug;
  //   this.taskSandbox.getSubtasksforselTask();
  //   this.subtaskarray.push(1);
  //   this.showSubTaskSection = !this.showSubTaskSection;
  //   if(this.showSubTaskSection){
  //     this.subtaskIntent =  2;
  //     this.subtaskarray.push(1);
  //   }else{
  //     this.subtaskarray = [];
  //   }   
  //   //this.taskDataService.getTasks.taskList[index].showSubTask = !this.taskDataService.getTasks.taskList[index].showSubTask;

  // }

  // /* toogle sub task listing */
  // toogleSubTaskOfSubtask($event, index): void {
  //   $event.stopPropagation();
  //   this.taskDataService.taskDetails.selectedTask = this.taskDataService.getTasks.subtasksList[index].slug;
  //   this.taskSandbox.getSubtasksforselTask();
  //   if((index + 1) ===   this.subtaskarray.length){
  //     this.subtaskarray.push(1);
  //   }else{
  //     this.subtaskarray.push(index);
  //   }
   

  //   //this.taskDataService.getTasks.taskList[index].showSubTask = !this.taskDataService.getTasks.taskList[index].showSubTask;

  // }

  /* View Task Details */
  viewTaskDetails(index): void {
    this.spinner.show();
    this.taskDataService.taskDetails.selectedTask = this.taskDataService.getTasks.taskList[index].slug;
    this.router.navigate([{outlets:{detailpopup:['task-detail',this.taskDataService.taskDetails.selectedTask]}}], {
      relativeTo: this.route.parent // <--- PARENT activated route.
  }); 
   // this.router.navigate([{ outlets: { detailpopup: ['task-detail'] } }], { skipLocationChange: true });
  }

  /* View SubTask Details */
  viewSubtaskDetails(index): void {
    this.spinner.show();
    this.taskDataService.taskDetails.selectedTask = this.taskDataService.getTasks.subtasksList[index].slug;

  }

  updateDueDate(date, selTask): void {
    this.taskDataService.taskPartialUpdates.task_slug = selTask.slug;
    this.taskDataService.taskPartialUpdates.end_date = this.utilityService.convertToUnix(date);
    this.taskSandbox.partialUpdateTask();
  }
}
