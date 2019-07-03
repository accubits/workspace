import { Injectable } from '@angular/core';
import merge from 'deepmerge'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Observable } from 'rxjs/Observable';
import "rxjs/add/operator/share";
import { TaskDataService } from '../shared/services/task-data.service';
import { TaskApiService } from '../shared/services/task-api.service';
import { AngularDateTimePickerModule } from 'angular2-datetimepicker';
import { ToastService } from '../shared/services/toast.service';
import { UtilityService } from '../shared/services/utility.service';
import { Router, ActivatedRoute } from '@angular/router';
import { Location } from '@angular/common';


@Injectable()
export class TaskSandbox {

  constructor(
    public taskDataService: TaskDataService,
    private taskApiService: TaskApiService,
    private toastService: ToastService,
    private spinner: Ng4LoadingSpinnerService,
    private utilityService: UtilityService,
    private router: Router,
    private route: ActivatedRoute,
    private location: Location
  ) { }

  /* Sandbox to handle API call for getting taks[Start] */
  getTaskList() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.getTasks().subscribe((result: any) => {
      if (this.taskDataService.getTasks.selectedTab !== 'overview') {
        this.taskDataService.getTasks.taskList = result.data.task;
        for (let i = 0; i < this.taskDataService.getTasks.taskList.length; i++) {
          if (this.taskDataService.getTasks.taskList[i].checklist.total === 0) {
            this.taskDataService.getTasks.taskList[i].checklist.percentage = '0%'
          } else {
            let percenatge = Math.round((this.taskDataService.getTasks.taskList[i].checklist.checked / this.taskDataService.getTasks.taskList[i].checklist.total) * 100);
            this.taskDataService.getTasks.taskList[i].checklist.percentage = percenatge.toString() + '%'
          }
          this.taskDataService.getTasks.taskList[i].due_date = new Date(this.taskDataService.getTasks.taskList[i].due_date * 1000)
        }
      } else {
        this.taskDataService.getTasks.overviewTaskList = result.data.task;
        for (let i = 0; i < this.taskDataService.getTasks.overviewTaskList.length; i++) {
          if (this.taskDataService.getTasks.overviewTaskList[i].checklist.total === 0) {
            this.taskDataService.getTasks.overviewTaskList[i].checklist.percentage = '0%'
          } else {
            let percenatge = Math.round((this.taskDataService.getTasks.overviewTaskList[i].checklist.checked / this.taskDataService.getTasks.overviewTaskList[i].checklist.total) * 100);
            this.taskDataService.getTasks.overviewTaskList[i].checklist.percentage = percenatge.toString() + '%'
          }
          this.taskDataService.getTasks.overviewTaskList[i].due_date = new Date(this.taskDataService.getTasks.overviewTaskList[i].due_date * 1000)
        }
      }

      this.taskDataService.getTasks.total = result.data.total;
      this.taskDataService.getTasks.taskOverViewData = result.data.overview;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting getting taks[End] */

  /* Sandbox to handle API call for getting  responsible Person[Start] */
  getReposiblePerson() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.getResponsiblePersons().subscribe((result: any) => {
      this.taskDataService.responsiblePersons.list = result.data;
      if (this.taskDataService.responsiblePersons.list.length === 0) {
        this.toastService.Info('', 'No result found')
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting  responsible Person[End] */

  /* Sandbox to handle API call for getting  Task Status[Start] */
  getTaskStat() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.getTakStatus().subscribe((result: any) => {
      this.taskDataService.taskStatus.list = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting Task Status[End] */

  /* Sandbox to handle API call for getting  responsible Person[Start] */
  getParentTaks() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.getParentTaks().subscribe((result: any) => {
      this.taskDataService.parentTasks.list = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting  responsible Person[End] */

  /* Sandbox to handle API call for managing task status[Start] */
  manageTaskStatus() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.manageTaskStatus().subscribe((result: any) => {
      this.taskDataService.taskRunManagement.showPopup = false;
      this.taskDataService.resetTaskRunManagement();
      this.getTaskList()
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for managing task status[End] */

  /* Sandbox to handle API call for Creating the task[Start] */
  createNewTask() {

    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.createNewTask().subscribe((result: any) => {
      this.taskDataService.apicall.inprogress = false;
      this.taskDataService.resetCreateTask();
      this.getTaskList();
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.taskDataService.apicall.inprogress = false;
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for Creating the task[End] */

  /* Sandbox to handle API call for Creating the filter[Start] */
  createNewFilter() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.createNewFilter().subscribe((result: any) => {
      //console.log(result.data.message);
      this.taskDataService.resetFilter();
      this.getFilterLists();
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for Creating the filter[End] */

  /* Sandbox to handle API call for getting  Task filter List[Start] */
  getFilterLists() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.getFilterLists().subscribe((result: any) => {
      this.taskDataService.filterList.list = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting  Task filter List[End] */


  /* Sandbox to handle API call for deleting  Task filter List[Start] */
  deleteFilter() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.deleteFilter().subscribe((result: any) => {
      this.taskDataService.deleteTaskFilter.deleteTaskFilterSlug = result.data;
      this.getFilterLists();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })

  }
  /* Sandbox to handle API call for deleting  Task filter List[End] */

  /* Sandbox to handle API call for Editing Filter[Start] */
  editFilter() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.editFilter().subscribe((result: any) => {
      this.taskDataService.editTaskFilter.selectedTaskFilterSlug = result.data;
      this.taskDataService.createTaskFilter = result.data;
      this.taskDataService.createTaskFilter.dueDate = this.taskDataService.createTaskFilter.dueDate ? new Date(this.taskDataService.createTaskFilter.dueDate * 1000) : null;
      this.taskDataService.createTaskFilter.startDate = this.taskDataService.createTaskFilter.startDate ? new Date(this.taskDataService.createTaskFilter.startDate * 1000) : null;
      this.taskDataService.createTaskFilter.finishedOn = this.taskDataService.createTaskFilter.finishedOn ? new Date(this.taskDataService.createTaskFilter.finishedOn * 1000) : null;
      this.spinner.hide();
    },
      err => {
        //alert("fdf");
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for Editing Filter[End] */
  editTask() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.editTask().subscribe((result: any) => {
      this.taskDataService.apicall.inprogress = false;
      this.taskDataService.resetCreateTask();
      this.taskDataService.taskDetails.showEditTaskPop = false;
      this.spinner.hide();
      this.toastService.Success(result.data);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.taskDataService.apicall.inprogress = false;
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for Creating the task[End] */

  /* Fetch Selected Task Details[Start] */
  fetchSelectedTaskDetails() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.fetchSelectedTaskDetails().subscribe((result: any) => {
      this.taskDataService.taskDetails.selectedTaskDetails = result.data;
      this.taskDataService.taskPartialUpdates.oldStart_date = new Date(this.taskDataService.taskDetails.selectedTaskDetails.startDate * 1000);
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Fetch Selected Task Details[End] */

  /* Fetch Selected Task Details[Start] */
  fetchSelectedTaskDetailsEdit() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.fetchSelectedTaskDetailsEdit().subscribe((result: any) => {
      this.taskDataService.editTaskTemplate = merge(this.taskDataService.editTaskTemplate, result.data);
      this.taskDataService.editTaskTemplate.endDate = this.taskDataService.editTaskTemplate.endDate ? this.utilityService.convertTolocale(this.taskDataService.editTaskTemplate.endDate) : null;
      this.taskDataService.editTaskTemplate.startDate = this.taskDataService.editTaskTemplate.startDate ? this.utilityService.convertTolocale(this.taskDataService.editTaskTemplate.startDate) : null;
      this.taskDataService.editTaskTemplate.reminder = this.taskDataService.editTaskTemplate.reminder ? this.utilityService.convertTolocale(this.taskDataService.editTaskTemplate.reminder) : null;
      if (this.taskDataService.editTaskTemplate.existingFiles.length > 0) {
        this.taskDataService.editTaksPopManagement.showAttachments = true;
      }

      if (Object.keys(this.taskDataService.editTaskTemplate.repeat).length !== 0) {
        if (this.taskDataService.editTaskTemplate.repeat.ends.never) {
          this.taskDataService.editTaskTemplate.taskEndOption = "never";
        } else if (this.taskDataService.editTaskTemplate.repeat.ends.on) {
          this.taskDataService.editTaskTemplate.taskEndOption = "on";
          this.taskDataService.editTaskTemplate.repeat.ends.on = this.taskDataService.editTaskTemplate.repeat.ends.on ? this.utilityService.convertTolocale(this.taskDataService.editTaskTemplate.repeat.ends.on) : null;
        } else {
          this.taskDataService.editTaskTemplate.taskEndOption = "after";
        }
        this.taskDataService.editTask.showRepeat = true;

      } else {
        this.taskDataService.editTask.showRepeat = false;
      }

      this.spinner.hide();

    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Fetch Selected Task Details[End] */

  /* Load template in create task[Start] */
  loadFromTemplate() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.loadFromTemplate().subscribe((result: any) => {
      this.taskDataService.createTask = merge(this.taskDataService.createTask, result.data);
      this.taskDataService.createTask.endDate = this.taskDataService.createTask.endDate ? this.utilityService.convertTolocale(this.taskDataService.createTask.endDate) : null;
      this.taskDataService.createTask.startDate = this.taskDataService.createTask.startDate ? this.utilityService.convertTolocale(this.taskDataService.createTask.startDate) : null;
      this.taskDataService.createTask.reminder = this.taskDataService.createTask.reminder ? this.utilityService.convertTolocale(this.taskDataService.createTask.reminder) : null;
      if (this.taskDataService.createTask.existingFiles.length > 0) {
        this.taskDataService.createTaksPopManagement.showAttachments = true;
      }
      if (this.taskDataService.createTask.checklists.length > 0) {
        this.taskDataService.createTaksPopManagement.showCheckList = true;
      }

      if (this.taskDataService.createTask.repeat.ends.never) {
        this.taskDataService.createTask.taskEndOption = "never";
      } else if (this.taskDataService.createTask.repeat.ends.on) {
        this.taskDataService.createTask.taskEndOption = "on";
      } else {
        this.taskDataService.createTask.taskEndOption = "after";
      }
      this.spinner.hide();

    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Load template in create task[End] */

  /* Fetch comments for Selected Task [Start] */
  fetchCommentForSelTask() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.fetchCommentForSelTask().subscribe((result: any) => {
      this.taskDataService.taskDetails.comments = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Fetch comments for Selected Task [Start] */

  /* like comments for Selected Task [Start] */
  likeComment() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.likeComment().subscribe((result: any) => {
      if (this.taskDataService.taskDetails.selectedTask) {
        this.fetchCommentForSelTask();
      }

      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* like comments for Selected Task [Start] */


  /* Fetch substask for Selected Task [Start] */
  getSubtasksforselTask() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.getSubtasksforselTask().subscribe((result: any) => {
     
      this.taskDataService.getTasks.subtasksList = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Fetch subtask for Selected Task [Start] */

  /* Add Comments for Selected Task [Start] */
  addCommentsForSelTask() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.addCommentsForSelTask().subscribe((result: any) => {
     
      this.taskDataService.taskDetails.subtasks = result.data;
      this.taskDataService.taskDetails.addedCommet = '';
      this.fetchCommentForSelTask()
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Add Comments for Selected Task [Start] */

  /* Delete Selected Task [Start] */
  deleteTask() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.deleteTask().subscribe((result: any) => {
     // this.taskDataService.detailPopup.show = false;
      this.taskDataService.deletePopup.show = false;
    //   this.router.navigate(
    //     [
    //         {
    //             outlets: {
    //               detailpopup: null
    //             }
    //         }
    //     ],
    //     {
    //         relativeTo: this.route.parent
    //     }
    // );
    this.location.back();
      this.getTaskList();
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Delete Task [Start] */

  /* Delete Selected Task [Start] */
  archiveTask() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.archiveTask().subscribe((result: any) => {
      this.taskDataService.archivePopup.show = false;
      this.taskDataService.taskRunManagement.selectedAll = false; this.taskDataService.taskRunManagement.selectedAll = false;
      this.getTaskList();
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Delete Task [Start] */

  /* Delete Buld Task [Start] */
  deleteBulkTasks() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.deleteBulkTask().subscribe((result: any) => {
      this.taskDataService.detailPopup.show = false;
      this.taskDataService.deleteBulkPopup.show = false;
      this.taskDataService.taskRunManagement.selectedAll = false;
      this.taskDataService.taskRunManagement.selectedTaskIds = [];
      this.getTaskList();
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Delete Bulk Task [Start] */

  /* priority/fav  Task [Start] */
  confirmPriorityOrfav() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.confirmPriorityOrfav().subscribe((result: any) => {
      this.taskDataService.priorityorfav.show = false;
      this.taskDataService.taskRunManagement.showPopup = false;
      this.taskDataService.taskRunManagement.selectedAll = false;
      this.taskDataService.taskBulkPriorityOrFav.taskSlugs = [];
      this.taskDataService.taskRunManagement.selectedTaskIds = [];
      this.getTaskList();
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* priority/fav  Task  [Start] */

  /* Partial Update Task [Start] */
  partialUpdateTask() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.partialUpdateTask().subscribe((result: any) => {
     this.getTaskList();
      if (this.taskDataService.taskDetails.selectedTask) {
        this.fetchSelectedTaskDetails();
      }
      this.taskDataService.resetPartialUpdates();
      this.spinner.hide();
    },
      err => {
        this.spinner.hide();
        this.toastService.Error(err.error.error.msg);
      })
  }
  /* Partial Update Task [End] */


  /*Update Task Status [Start] */
  updateTaskStatus() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.updateTaskStatus().subscribe((result: any) => {
      this.getTaskList();
      this.taskDataService.resetUpdateTask();
      this.fetchSelectedTaskDetails();
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Update Task Status  [End] */

  /*Update Task Status [Start] */
  updatecheckListStatus() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.updatecheckListStatus().subscribe((result: any) => {
     
      //this.getTaskList();
      this.fetchSelectedTaskDetails();
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Update Task Status  [End] */

  /* Get Task Template [Start] */
  getTaskTemplates() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.getTaskTemplate().subscribe((result: any) => {
      this.taskDataService.taskTemplates.lists = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Get Task Template [End] */

}
