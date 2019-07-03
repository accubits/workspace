
import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { CookieService } from 'ngx-cookie-service';
import { Configs } from '../../config';
import { TaskDataService } from '../../shared/services/task-data.service';
import { UtilityService } from '../../shared/services/utility.service';
import { TaskSandbox } from '../task.sandbox';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Router, ActivatedRoute } from '@angular/router';
import { SettingsDataService } from '../../shared/services/settings-data.service';
import merge from 'deepmerge'
import { SettingsApiService } from '../../shared/services/settings-api.service';
import { ToastService } from '../../shared/services/toast.service'

@Component({
  selector: 'app-task-detailpopup',
  templateUrl: './task-detailpopup.component.html',
  styleUrls: ['./task-detailpopup.component.scss']
})
export class TaskDetailpopupComponent implements OnInit {
  activeBtmTab: string = 'comment';
  user: string;
  userSlug: string;
  status: boolean = false;
  ckStatus: boolean = false;
  common_popup:boolean =  false;
  addReminderValue: boolean = false;
  myDate: any;

  constructor(
    public taskDataService: TaskDataService,
    private taskSandbox: TaskSandbox,
    private cookieService: CookieService,
    private utilityService: UtilityService,
    private spinner: Ng4LoadingSpinnerService,
    private http: HttpClient,
    private location: Location,
    private router: Router,
    private route: ActivatedRoute,
    public settingsDataService: SettingsDataService,
    public SettingsApiService: SettingsApiService,
    private toastService: ToastService,

  ) {
    this.route.params.subscribe(params => {
      if (params['selectedTaskslug']) {
        this.taskDataService.taskDetails.selectedTask = params['selectedTaskslug'];
        this.taskSandbox.fetchSelectedTaskDetails();
        this.taskSandbox.fetchSelectedTaskDetailsEdit();
        this.activeBtmTab === 'comment' ? this.taskSandbox.fetchCommentForSelTask() : this.taskSandbox.getSubtasksforselTask();
        window.scrollTo(0, 0)
      }
    });
    
    
  }

  public assetUrl = Configs.assetBaseUrl;
  ngOnInit() {
    this.activeBtmTab = 'comment';
    this.user = this.cookieService.get('userName');
    this.userSlug = this.cookieService.get('userSlug');
    this.taskDataService.detailPopup.show = true;
    this.SettingsApiService.fetchProfileDetailsEdit().subscribe((result: any) => {
      this.settingsDataService.editSettingsTemplate = merge(this.settingsDataService.editSettingsTemplate, result.data);
    });
 
  }

  ngOnDestroy() {
    this.taskDataService.detailPopup.show = false;
  }

  /* Download Attached File[Start] */
  downloadFile(file): void {
    var link = document.createElement('a');
    link.href = file;
    link.download = file;
    // document.body.appendChild(link);
    // link.click();
    window.open(link.href)
  }
  /* Download Attached File[End] */

  /* Switching tabs for comment listing and subtask Listing[Start] */
  switchBottomTabs(selTab): void {
    this.activeBtmTab = selTab;
    selTab === 'comment' ? this.taskSandbox.fetchCommentForSelTask() : this.taskSandbox.getSubtasksforselTask();

  }
  /* Switching tabs for comment listing and subtask Listing[End] */

  /* Adding comments */
  addCommentsForSelTask(): void {
    if (!this.taskDataService.taskDetails.addedCommet) {
      this.toastService.Error('Comment cannot be empty');
      return;
    }
    this.taskSandbox.addCommentsForSelTask();
  }

  /* cancel comments */
  cancelCommentsForSelTask(): void {
    this.taskDataService.taskDetails.addedCommet = '';
  }

  /* Delete Task */
  deleteTask(): void {
    this.taskDataService.deletePopup.show = true
  }
  /* Edit Task */

  /* Delete Task */
  archiveTask(): void {
    this.taskDataService.archivePopup.show = true
  }
  /* Edit Task */
  editTask(): void {
    //  this.taskDataService.detailPopup.show = false;
    this.router.navigate(
      [
        {
          outlets: {
            detailpopup: null
          }
        }
      ],
      {
        relativeTo: this.route.parent
      }
    );
    this.taskDataService.taskDetails.showEditTaskPop = true;
  }

  /* Update Task Status-Status [Start]*/
  updateTaskStatus(status): void {
    this.taskDataService.updateTask.status = status;
    this.taskDataService.updateTask.action = 'single';
    if(this.taskDataService.updateTask.tasks.indexOf(this.taskDataService.taskDetails.selectedTask)=== -1){
      this.taskDataService.updateTask.tasks.push(this.taskDataService.taskDetails.selectedTask);  // Inserting in to slected task list;
    }

    this.taskSandbox.updateTaskStatus();
  }
  /* Update Task Status-Status [End]*/

  /*Partial Updation(making a selected task fav) */
  makeFavourite(fav, pri): void {
    //  event.stopPropagation();
    this.taskDataService.taskPartialUpdates.favourite = fav;
    this.taskDataService.taskPartialUpdates.task_slug = this.taskDataService.taskDetails.selectedTask;
    this.taskDataService.taskPartialUpdates.priority = pri
    this.taskSandbox.partialUpdateTask();
  }

  /*Partial Updation(making a selected task priority) */
  makePriority(pri, fav): void {
    //  event.stopPropagation();
    this.taskDataService.taskPartialUpdates.priority = pri;
    this.taskDataService.taskPartialUpdates.task_slug = this.taskDataService.taskDetails.selectedTask;
    this.taskDataService.taskPartialUpdates.favourite = fav;
    this.taskSandbox.partialUpdateTask();
  }

  /* Change Due Date  */
  changeDueDate(event): void {

      
    this.taskDataService.taskPartialUpdates.end_date = this.utilityService.convertToUnix(event.value);
    this.taskDataService.taskPartialUpdates.task_slug = this.taskDataService.taskDetails.selectedTask;
    this.taskSandbox.partialUpdateTask();
  }

  /* like a comment  */
  likeComment(idx): void {
    this.taskDataService.taskDetails.likeStatus = !this.taskDataService.taskDetails.comments[idx].like.meLiked;
    this.taskDataService.taskDetails.selectedcomment = this.taskDataService.taskDetails.comments[idx].commentSlug
    this.taskSandbox.likeComment();
  }
  atAct(): void {
    this.status = !this.status;
  }
  ckAct(): void {
    this.ckStatus = !this.ckStatus;
  }

  closeDetailPop(): void {
    this.location.back();
    this.taskDataService.resetSelectedTaskDetails();
    this.taskDataService.detailPopup.show = false
  }

  /* Partial updating checklist */
  updatecheckListStatus(checkItem): void {
    this.taskDataService.updateCheckList.checklistSlug = checkItem.checklistSlug;
    this.taskDataService.updateCheckList.checklistStatus = checkItem.checklistStatus;
    this.taskSandbox.updatecheckListStatus();
  }

  /* View SubTask Details */
  viewSubtaskDetails(index): void {
    this.spinner.show();
    this.taskDataService.taskDetails.selectedTask = this.taskDataService.getTasks.subtasksList[index].slug;
    this.taskDataService.detailPopup.show = true;
    this.router.navigate(['./', { outlets: { detailpopup: ['task-detail', this.taskDataService.taskDetails.selectedTask] } }], {
      relativeTo: this.route.parent // <--- PARENT activated route.
    });

  }

  showSubtaskPeople(event): void {
    event.stopPropagation();
    this.common_popup = !this.common_popup
  }


  addReminder(){
   this.addReminderValue = true;
   this.taskDataService.taskDetails.selectedTaskDetails.reminder = new Date().getTime() / 1000;
   this.taskDataService.taskPartialUpdates.reminder = this.utilityService.convertToUnix(new Date());
    this.taskDataService.taskPartialUpdates.task_slug = this.taskDataService.taskDetails.selectedTask;
    this.taskSandbox.partialUpdateTask();
  }

  changeReminder(event){
    this.taskDataService.taskPartialUpdates.reminder = this.utilityService.convertToUnix(event.value);
    this.taskDataService.taskPartialUpdates.task_slug = this.taskDataService.taskDetails.selectedTask;
    this.taskSandbox.partialUpdateTask();

  }

  clearReminder(){
    this.taskDataService.taskPartialUpdates.reminder = null;
    this.taskDataService.taskPartialUpdates.task_slug = this.taskDataService.taskDetails.selectedTask;
    this.taskSandbox.partialUpdateTask();
  }
  closeDate()
  {
    this.taskDataService.taskDetails.selectedTaskDetails.reminder = false;
  }
}
