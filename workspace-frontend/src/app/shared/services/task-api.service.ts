import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Observable } from "rxjs/Observable";
import { CookieService } from 'ngx-cookie-service';
import { Routes, RouterModule, Router } from '@angular/router';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';
import 'rxjs/add/observable/throw';
import { Configs } from '../../config';
import { TaskDataService } from './task-data.service';
import { TaskPostDataService } from './task-post-data.service';
import { ActStreamDataService } from './act-stream-data.service';

@Injectable()
export class TaskApiService {

  constructor(
    private cookieService: CookieService,
    public taskDataService: TaskDataService,
    private taskPostDataService: TaskPostDataService,
    private actStreamDataService: ActStreamDataService,
    private http: HttpClient
  ) { }

  /* API call for get tasks[Start] */
  getTasks(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/fetchall';
    let data = {
      "page": this.taskDataService.getTasks.page,
      "perPage": this.taskDataService.getTasks.perPage,
      "tab": this.taskDataService.getTasks.selectedTab,
      'q': this.taskDataService.getTasks.searchText,
      'sortBy': this.taskDataService.getTasks.selectedSortItem,
      'sortOrder': this.taskDataService.getTasks.sortOrder,
      'filterBy': this.taskDataService.getTasks.isFilterdBy ? this.taskDataService.createTaskFilter : null,
      'orgSlug': this.cookieService.get('orgSlug'),
    }

    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for get tasks[end] */

  /* Fetch sub taks for Selected Task [Start] */
  getSubtasksforselTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/subtasks'
    let data = { "task_slug": this.taskDataService.taskDetails.selectedTask }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Fetch sub taks for Selected Task [End] */


  /* Get Rsponsible Person[Start] */
  getResponsiblePersons(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/orguserlist'
    let data = {
      'org_slug': this.cookieService.get('orgSlug'),
      'q': this.taskDataService.responsiblePersons.searchText,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Get Rsponsible Person[End] */


  /* Get Filter List[Start] */
  getFilterLists(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/list-task-filter'
    let data = {
      'org_slug': this.cookieService.get('orgSlug'),
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));

  }
  /* Get Filter List[End] */

  /* Get Task Status[Start]*/
  getTakStatus(): Observable<any> {
    let URL = Configs.api + 'taskmanagement/list-task-status'
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Get Task Status[Start] */

  /* Get Parent Task[Start] */
  getParentTaks(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/parent-tasks?q=' + this.taskDataService.parentTasks.searchText
    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Get Parent Task[End] */

  /* Creating new task[Start] */
  createNewTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task';
    var fd = new FormData();
    fd.append('data', this.taskPostDataService.prepareCreateTaskPost());
    for (let i = 0; i < this.taskDataService.createTask.fileList.length; i++) {
      fd.append('file[]', this.taskDataService.createTask.fileList[i]);
    }
    // Preparing HTTP Call
    return this.http.post(URL, fd)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Creating new task[End] */


  /* Creating new filter[Start] */
  createNewFilter(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task-filter';
    let data = this.taskPostDataService.prepareCreateTaskFilterPost();
    if(this.taskDataService.createTaskFilter.filterSlug){
      URL = Configs.api + 'taskmanagement/task-filter-update';
    }

    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Creating new filter[End] */

  errorHandler(error) {
    return error
  }

  /* Editing filter[Start] */
  editFilter(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task-filter/' + this.taskDataService.editTaskFilter.selectedTaskFilterSlug + '/edit'
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Editing filter[End] */

  /* Deleting filter[Start] */
  deleteFilter(): Observable<any> {
    let URL = Configs.api + 'taskmanagement/task-filter/' + this.taskDataService.deleteTaskFilter.deleteTaskFilterSlug;
    return this.http.delete(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Deleting filter[End] */


  /* Creating new task[Start] */
  editTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task/' + this.taskDataService.taskDetails.selectedTask;
    var fd = new FormData();
    fd.append('data', this.taskPostDataService.prepareEditTaskPost());
    for (let i = 0; i < this.taskDataService.editTaskTemplate.newFileList.length; i++) {
      fd.append('file[]', this.taskDataService.editTaskTemplate.newFileList[i]);
    }

    // Preparing HTTP Call
    return this.http.post(URL, fd)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Creating new task[End] */


  /* Manage Task Status[Start] */
  manageTaskStatus(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/update-task-status'
    let data = this.taskPostDataService.prepareManagePost();
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Manage Task Status[End] */

  /* Fetch Selected Task Details[Start] */
  fetchSelectedTaskDetails(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task-details'
    let data = { "task_slug": this.taskDataService.taskDetails.selectedTask }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Fetch Selected Task Details[End] */

  /* Fetch Selected Task Details For Edit[Start] */
  fetchSelectedTaskDetailsEdit(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task/' + this.taskDataService.taskDetails.selectedTask + '/edit';

    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Fetch Selected Task Details For Edit[End] */

  /* Load from template[Start] */
  loadFromTemplate(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task/' + this.taskDataService.taskTemplates.selectedTemplateSlug + '/edit';

    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Load from template[End] */

  /* Load from template[Start] */
  loadFromTemplateActivity(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task/' + this.actStreamDataService.taskTemplates.selectedTemplateSlug + '/edit';

    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Load from template[End] */

  /* Fetch Selected Task Details[Start] */
  fetchCommentForSelTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task/' + this.taskDataService.taskDetails.selectedTask + '/comments'

    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Fetch Selected Task Details[End] */

  /* Add Comments for selected  task[Start] */
  addCommentsForSelTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/comment'
    let data = {
      "task_slug": this.taskDataService.taskDetails.selectedTask,
      "description": this.taskDataService.taskDetails.addedCommet,
      "reply_comment_slug": this.taskDataService.taskDetails.replySlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Add Comments for selected  task[End] */


  /* like a Comment for selected  task[Start] */
  likeComment(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/comment/' + this.taskDataService.taskDetails.selectedcomment + '/like'
    let data = {
      "like": this.taskDataService.taskDetails.likeStatus
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* like a Comment for selected  task[End] */

  /* Delete selected  task[Start] */
  deleteTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task/' + this.taskDataService.taskDetails.selectedTask;

    // Preparing HTTP Call
    return this.http.delete(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Delete  task[End] */

  /* Delete selected  task[Start] */
  archiveTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/addToArchive';
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "taskSlug": this.taskDataService.taskDetails.selectedTask
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Delete  task[End] */

  /* Delete selected  task[Start] */
  deleteBulkTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/taskBulkDelete';

    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "taskSlugs": this.taskDataService.taskRunManagement.selectedTaskIds
    }


    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Delete  task[End] */
  
  /* pririty or fav selected  task[Start] */
  confirmPriorityOrfav(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/favOrPriorityMultipleUpdate';

    let data = this.taskDataService.taskBulkPriorityOrFav;


    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
   /* pririty or fav selected  task[End] */

  /* partial update  task[Start] */
  partialUpdateTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/partial-update'
    let data = this.taskDataService.taskPartialUpdates;

    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error || 'Server error.'));
  }
  /* partial update  task[End] */

  /* Get Task Template[Start] */
  getTaskTemplate(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task-templates'

    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Get Task Template[End] */


  /* Update Task status[Start] */
  updateTaskStatus(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/update-task-status';
    let data = this.taskDataService.updateTask;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Update Task status[End] */

  /* Update checklist status[Start] */
  updatecheckListStatus(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/changeChecklistStatus';
    let data = this.taskDataService.updateCheckList;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
   /* Update checklist status[End] */


  /* Generic function to check Responses[Start] */
  checkResponse(response: any) {
    let results = response
    if (results.status) {
      return results;
    }
    else {
      console.log("Error in API");
      return results;
    }
  }
  /* Generic function to check Responses[End] */
}
