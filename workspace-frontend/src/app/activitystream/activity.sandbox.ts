import { Injectable } from '@angular/core';
import merge from 'deepmerge'
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { ActivityApiService } from '../shared/services/activity-api.service';
import { ActStreamDataService } from '../shared/services/act-stream-data.service';
import { ToastService } from '../shared/services/toast.service';
import { TaskApiService } from '../shared/services/task-api.service'
import { CookieService } from 'ngx-cookie-service';

@Injectable()
export class ActivitySandboxService {

  constructor(
    public actStreamDataService: ActStreamDataService,
    public activityApiService: ActivityApiService,
    private toastService: ToastService,
    private spinner: Ng4LoadingSpinnerService,
    private taskApiService: TaskApiService,
    private cookieService: CookieService
  ) { }

  /* Fetch Selected activity Details[Start] */
  fetchActivityStream() {
    this.spinner.show();
    // Accessing task API service
    return this.activityApiService.fetchActivityStream().subscribe((result: any) => {
      this.actStreamDataService.fetchActivityStream.streamData = result.data.streamData;
      this.actStreamDataService.fetchActivityStream.total = result.data.total;
      this.actStreamDataService.fetchActivityStream.to = result.data.to;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Fetch Selected activity Details[End] */

   /* Fetch Selected activity Details[Start] */
   getActivityStream() {
    this.spinner.show();
    // Accessing task API service
    return this.activityApiService.fetchActivityStream().subscribe((result: any) => {
      for(let i = 0; i<result.data.streamData.length; i++){
        this.actStreamDataService.fetchActivityStream.streamData.push(result.data.streamData[i]);
      }
      if(this.actStreamDataService.fetchActivityStream.streamData.length > 20){
        this.actStreamDataService.fetchActivityStream.scrollArrow = true;
      }
      this.actStreamDataService.fetchActivityStream.total = result.data.total;
      this.actStreamDataService.fetchActivityStream.to = result.data.to;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Fetch Selected activity Details[End] */

  /* Sandbox to handle API call for getting  responsible Person[Start] */
  getReposiblePerson() {
    this.spinner.show();
    // Accessing task API service
    return this.activityApiService.getResponsiblePersons().subscribe((result: any) => {
      this.actStreamDataService.responsiblePersons.list = result.data;
      this.spinner.hide();
      if (this.actStreamDataService.toUsers.toUsers.length > 0) {
        for (let i = 0; i < this.actStreamDataService.toUsers.toUsers.length; i++) {
          let selUserinList = this.actStreamDataService.responsiblePersons.list.filter(
            user => user.slug === this.actStreamDataService.toUsers.toUsers[i].userSlug)[0];
            if (selUserinList) {
              let idx = this.actStreamDataService.responsiblePersons.list.indexOf(selUserinList)
              this.actStreamDataService.responsiblePersons.list[idx]['existing'] = true;
            }
          }
      }
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting  responsible Person[End] */

  /* Sandbox to handle API call for getting  responsible Person[Start] */
  getRewardparticipants() {
    this.spinner.show();
    // Accessing task API service
    return this.activityApiService.getRewardparticipants().subscribe((result: any) => {
      this.actStreamDataService.rewardPersons.list = result.data;
      this.spinner.hide();

      if (this.actStreamDataService.createAppreciation.recipients.length > 0) {
        for (let i = 0; i < this.actStreamDataService.createAppreciation.recipients.length; i++) {
          let selUserinList = this.actStreamDataService.rewardPersons.list.filter(
            user => user.slug === this.actStreamDataService.createAppreciation.recipients[i].userSlug)[0];
            if (selUserinList) {
              let idx = this.actStreamDataService.rewardPersons.list.indexOf(selUserinList)
              this.actStreamDataService.rewardPersons.list[idx]['existing'] = true;
            }
          }
      }
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting  responsible Person[End] */

  /* Sandbox to handle API call for getting task widget details[Start] */
  getTaskWidgetDetails() {
    this.spinner.show();
    // Accessing task API service
   return this.activityApiService.getTaskWidgetDetails().subscribe((result: any) => {
      this.actStreamDataService.taskWidgetDetails.details = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting task widget details[End] */


  /* call function for reset responsible persions[Start] */
  resetResponsiblePerson() {
    for (let i = 0; i < this.actStreamDataService.responsiblePersons.list.length; i++) {
      this.actStreamDataService.responsiblePersons.list[i].existing = false;
    }
  }
  /* call function for reset responsible persions[end] */

  /* call function for reset data[Start] */
  resetData() {
    for (let i = 0; i < this.actStreamDataService.responsiblePersons.list.length; i++) {
      this.actStreamDataService.responsiblePersons.list[i].existing = false;
    }
    this.actStreamDataService.msgEditor.text = '';
    this.actStreamDataService.resetActivityStream();
    this.actStreamDataService.resetPopUpBox();
    this.actStreamDataService.toUsers.toAllEmployee = false;
    this.actStreamDataService.moreOption.show = false;
    this.actStreamDataService.toUsers.toUsers = [];
    this.actStreamDataService.createTask.assignees = [];
    this.actStreamDataService.createAppreciation.recipients = [];
    this.actStreamDataService.addDisplayPeriod.show = false;
    this.actStreamDataService.responsiblePersons.searchParticipantsTxt = '';
    this.actStreamDataService.rewardPersons.searchRewardParticipantsTxt = '';
  }
  /* call function for reset data[Start] */

  /************************* message ************************* /
/* Sandbox to handle API call for Creating new message[Start] */
  createNewMessage() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.createNewMessage().subscribe((result: any) => {
      this.resetData();
      this.fetchActivityStream();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for Creating new message[End] */

  /* Sandbox to handle API call for get Message Comments[Start] */
  getMessageComments(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.getMessageCommentsAndResponses().subscribe((result: any) => {
      /* accessing message comments and reply count */
      this.actStreamDataService.fetchActivityStream.streamData[index].comments = result.data.messageComments;
      for (let i = 0; i < this.actStreamDataService.fetchActivityStream.streamData[index].comments.length; i++) {
        let replyCount = this.actStreamDataService.fetchActivityStream.streamData[index].comments.filter(
          file => file.msgParentCommentSlug === this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].msgCommentSlug).length;
        this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].replyCount = replyCount;
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get Message Comments[end] */

  /* Sandbox to handle API call for get Message Comment reply[Start] */
  getMessageCommentReply(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.getMessageCommentsAndResponses().subscribe((result: any) => {
      /* accessing comments reply */
      for (let i = 0; i < result.data.messageComments.length; i++) {
        let Reply = result.data.messageComments.filter(
          file => file.msgParentCommentSlug === comment.msgCommentSlug)[i];
        if (Reply) {
          this.actStreamDataService.CommentsAndResponses.getCommentReply.push(Reply);
        }
      }
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].reply = this.actStreamDataService.CommentsAndResponses.getCommentReply;
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].replyCount = this.actStreamDataService.CommentsAndResponses.getCommentReply.length;
      this.actStreamDataService.CommentsAndResponses.getCommentReply = [];
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get Message Comment reply[end] */

  /* Sandbox to handle API call for add Message response[Start] */
  messageResponse(index) {
    // Accessing activitystream API service
    return this.activityApiService.messageResponse().subscribe((result: any) => {
      /* accessing message response list */
      this.actStreamDataService.fetchActivityStream.streamData[index].message.yourMessageResponseSlug = result.data.messageResponseSlug;
      this.actStreamDataService.CommentsAndResponses.action = 'create';
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for add Message response[end] */

  /* Sandbox to handle API call for add Message comment[Start] */
  addMessageComment(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.messageComment().subscribe((result: any) => {
      this.getMessageComments(index);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
      this.actStreamDataService.CommentsAndResponses.commentTxt = '';
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for add Message comment[end] */

  /* Sandbox to handle API call for add Message comment reply[Start] */
  addMessageCommentReplay(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.messageComment().subscribe((result: any) => {
      this.getMessageCommentReply(comment, index, idx);
      this.actStreamDataService.CommentsAndResponses.commentSlug = null;
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
      this.actStreamDataService.CommentsAndResponses.commentTxt = '';
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for add Message comment reply[end] */

  /* Sandbox to handle API call for delete Message comment[Start] */
  deleteMessageComment(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.messageComment().subscribe((result: any) => {
      this.getMessageComments(index);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = null;
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for delete Message comment[end] */

  /* Sandbox to handle API call for delete Message comment[Start] */
  deleteMessageCommentReplay(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.messageComment().subscribe((result: any) => {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.resetPopUpBox();
      this.getMessageCommentReply(comment, index, idx);
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for delete Message comment[end] */

  /* Sandbox to handle API call for get message comment response[Start] */
  getMessageCommentResponse(index) {
    return this.activityApiService.messageCommentResponse().subscribe((result: any) => {
      this.getMessageComments(index);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get message comment response[end] */

  /* Sandbox to handle API call for get message comment response[Start] */
  getReplyCommentResponse(comment, index, idx) {
    return this.activityApiService.messageCommentResponse().subscribe((result: any) => {
      this.getMessageCommentReply(comment, index, idx);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get message comment response[end] */
  /************************* message ************************* /

/*******************   Sandbox to handle API call for task management  ******************/
  /* Sandbox to handle API call for Creating the task[Start] */
  createNewTask() {
   this.spinner.show();
    // Accessing task API service
    return this.activityApiService.createNewTask().subscribe((result: any) => {
      this.resetData();
      this.fetchActivityStream();
      this.getTaskWidgetDetails();
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for Creating the task[End] */

  /* Sandbox to handle API call for get parent task[Start] */
  getParentTaks() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.getParentTaks().subscribe((result: any) => {
      this.actStreamDataService.parentTasks.list = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get parent task[End] */

  /* Sandbox to handle API call for Load template in create task[Start] */
  loadFromTemplate() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.loadFromTemplateActivity().subscribe((result: any) => {
      this.actStreamDataService.createTask = merge(this.actStreamDataService.createTask, result.data);
      this.actStreamDataService.createTask.endDate = this.actStreamDataService.createTask.endDate ? new Date(this.actStreamDataService.createTask.endDate) : null;
      this.actStreamDataService.createTask.startDate = this.actStreamDataService.createTask.startDate ? new Date(this.actStreamDataService.createTask.startDate) : null;
      this.actStreamDataService.createTask.reminder = this.actStreamDataService.createTask.reminder ? new Date(this.actStreamDataService.createTask.reminder) : null;
      if (this.actStreamDataService.createTask.repeat.ends.never) {
        this.actStreamDataService.createTask.taskEndOption = "never";
      } else if (this.actStreamDataService.createTask.repeat.ends.on) {
        this.actStreamDataService.createTask.taskEndOption = "on";
      } else {
        this.actStreamDataService.createTask.taskEndOption = "after";
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for Load template in create task[End] */

  /* Sandbox to handle API call for Get Task Template [Start] */
  getTaskTemplates() {
    this.spinner.show();
    // Accessing task API service
    return this.taskApiService.getTaskTemplate().subscribe((result: any) => {
      this.actStreamDataService.taskTemplates.lists = result.data;
      this.spinner.hide();
    },
      error => {
        console.log(error.msg);
        this.spinner.hide();
        this.toastService.Error(error.msg);
      })
  }
  /* Sandbox to handle API call for Get Task Template [End] */

  /* Sandbox to handle API call for update the task[Start] */
  updateTask() {
    this.spinner.show();
    // Accessing task API service
    return this.activityApiService.updateTask().subscribe((result: any) => {
      this.resetData();
      this.fetchActivityStream();
      this.spinner.hide();
      this.toastService.Success(result.data);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for update the task[End] */

  /* Sandbox to handle API call for get task details[start] */
  fechTaskDetails() {
    return this.activityApiService.fechTaskDetails().subscribe((result: any) => {
      this.actStreamDataService.createTask.priority = result.data.priority;
      this.actStreamDataService.createTask.favourite = result.data.favourite;
      if(result.data.startDate !== ""){
        this.actStreamDataService.createTask.startDate = new Date(result.data.startDate * 1000);
      }
      else{
        this.actStreamDataService.createTask.startDate = result.data.startDate;
      }
      if(result.data.reminder !== ""){
        this.actStreamDataService.createTask.reminder = new Date(result.data.reminder * 1000);
      }
      else{
        this.actStreamDataService.createTask.reminder = result.data.reminder;
      }
      if(result.data.endDate !== ""){
        this.actStreamDataService.createTask.endDate = new Date(result.data.endDate * 1000);
      }
      else{
        this.actStreamDataService.createTask.endDate = result.data.endDate;
      }
      if(result.data.assignees.length > 0){
        this.actStreamDataService.createTask.assignees = [];
        for (let i = 0; i < result.data.assignees.length; i++) {
          this.actStreamDataService.createTask.assignees.push({
            assigneeSlug: result.data.assignees[i].assigneeSlug,
            assigneeName: result.data.assignees[i].assigneeName
          });
        }
      }
      else{
        this.actStreamDataService.createTask.assignees = [];
      }
      this.actStreamDataService.createTask.responsiblePerson = result.data.responsiblePerson;
      this.actStreamDataService.createTask.fileList = [];
      for (let i = 0; i < result.data.existingFiles.length; i++) {
        this.actStreamDataService.createTask.fileList.push({
          name: result.data.existingFiles[i].name,
          size: result.data.existingFiles[i].size
        });
      }
      this.actStreamDataService.createTask.checklists = [];
      for (let i = 0; i < result.data.checklists.length; i++) {
        this.actStreamDataService.createTask.checklists.push({
          checklistStatus: result.data.checklists[i].checklistStatus,
          description: result.data.checklists[i].description
        });
      }
      this.actStreamDataService.createTask.responsiblePersonCanChangeTime = result.data.responsiblePersonCanChangeTime;
      this.actStreamDataService.createTask.approveTaskCompleted = result.data.approveTaskCompleted;
      this.actStreamDataService.createTask.parentTask = result.data.parentTask;
      this.actStreamDataService.createTask.approver = result.data.approver;
      this.actStreamDataService.toUsers.toAllEmployee = result.data.isAllParticipants;
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get task details[end] */

  /* Sandbox to handle API call for get task comments[Start] */
  getTaskComments(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.getTaskCommentsAndResponses().subscribe((result: any) => {
       /* accessing task comments and reply count */
      this.actStreamDataService.fetchActivityStream.streamData[index].comments = result.data;
      for (let i = 0; i < this.actStreamDataService.fetchActivityStream.streamData[index].comments.length; i++) {
        let replyCount = this.actStreamDataService.fetchActivityStream.streamData[index].comments.filter(
          file => file.taskParentCommentSlug === this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].commentSlug).length;
        this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].replyCount = replyCount;
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get task comments[end] */

    /* Sandbox to handle API call for add task comments[Start] */
    addtaskComment(index) {
      this.spinner.show();
      // Accessing activitystream API service
      return this.activityApiService.addTaskComment().subscribe((result: any) => {
        this.getTaskComments(index);
        this.actStreamDataService.CommentsAndResponses.action = 'create';
        this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
        this.actStreamDataService.CommentsAndResponses.commentTxt = '';
        this.spinner.hide();
        this.toastService.Success(result.data.message);
      },
        err => {
          console.log(err);
          this.spinner.hide();
          this.toastService.Error(err.msg);
        })
    }
    /* Sandbox to handle API call for add task comments[end] */

  /* Sandbox to handle API call for delete the task[Start] */
  deleteTask() {
    this.spinner.show();
    // Accessing task API service
    return this.activityApiService.deleteTask().subscribe((result: any) => {
      this.actStreamDataService.resetActivityStream();
      this.fetchActivityStream();
      this.actStreamDataService.resetPopUpBox();
      this.spinner.hide();
      this.toastService.Success(result.data);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for delete the task[End] */

  /* Sandbox to handle API call for add task comment response[Start] */
  addtaskCommentResponse(index) {
    return this.activityApiService.taskCommentResponse().subscribe((result: any) => {
      this.getTaskComments(index);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for add task comment response[end] */

  /* Sandbox to handle API call for get task comment response[Start] */
  getTaskCommentResponse(comment, index, idx) {
    return this.activityApiService.taskCommentResponse().subscribe((result: any) => {
      this.getTaskCommentReply(comment, index, idx);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get task comment response[end] */

  /* Sandbox to handle API call for add task comment reply[Start] */
  addTaskCommentReplay(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.addTaskComment().subscribe((result: any) => {
      this.getTaskCommentReply(comment, index, idx);
      this.actStreamDataService.CommentsAndResponses.commentSlug = null;
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.parentCommentSlug = '';
      this.actStreamDataService.CommentsAndResponses.commentTxt = '';
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for add task comment reply[end] */

  /* Sandbox to handle API call for delete task comment[Start] */
  deleteTaskComment(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.deleteTaskComment().subscribe((result: any) => {
      this.getTaskComments(index);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = null;
      this.spinner.hide();
      this.toastService.Success(result.data.message);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for delete task comment[end] */

  /* Sandbox to handle API call for get task Comment reply[Start] */
  getTaskCommentReply(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.getTaskCommentsAndResponses().subscribe((result: any) => {
      /* accessing comments reply */
      for (let i = 0; i < result.data.length; i++) {
        let Reply = result.data.filter(
          file => file.taskParentCommentSlug === comment.commentSlug)[i];
        if (Reply) {
          this.actStreamDataService.CommentsAndResponses.getCommentReply.push(Reply);
        }
      }
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].reply = this.actStreamDataService.CommentsAndResponses.getCommentReply;
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].replyCount = this.actStreamDataService.CommentsAndResponses.getCommentReply.length;
      this.actStreamDataService.CommentsAndResponses.getCommentReply = [];
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get task Comment reply[end] */

  /* Sandbox to handle API call for delete task comment[Start] */
  deleteTaskCommentReplay(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.deleteTaskComment().subscribe((result: any) => {
      this.actStreamDataService.resetPopUpBox();
      this.getTaskCommentReply(comment, index, idx);
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for delete task comment[end] */
  /*******************   Sandbox to handle API call for task management  ******************/

  /*******************   Sandbox to handle API call for event management  ******************/
  /* Creating the event[Start] */
  createNewEvent() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.createNewEvent().subscribe((result: any) => {
      this.resetData();
      this.fetchActivityStream();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Creating the event[End] */

  /* get event details[Start] */
  getEventDetails() {
    this.spinner.show();
     // Accessing activitystream API service
    return this.activityApiService.getEventDetails().subscribe((result: any) => {
      this.actStreamDataService.createEvent.eventCreatorSlug = result.data.eventCreatorSlug;
      this.actStreamDataService.msgEditor.text = result.data.eventDesc;
      this.actStreamDataService.createEvent.eventAllDay = result.data.eventAllDay;
      this.actStreamDataService.createEvent.eventAvailability = result.data.eventAvailability;
      this.actStreamDataService.createEvent.eventCreatedAt = result.data.eventCreatedAt;
      this.actStreamDataService.createEvent.eventCreatorEmail = result.data.eventCreatorEmail;
      this.actStreamDataService.createEvent.eventCreatorName = result.data.eventCreatorName;
      this.actStreamDataService.createEvent.eventTitle = result.data.eventTitle;
      this.actStreamDataService.createEvent.eventToAllEmployee = result.data.eventToAllEmployee;
      this.actStreamDataService.createEvent.eventLocation = result.data.eventLocation;
      this.actStreamDataService.createEvent.eventRepeat = result.data.eventRepeat;
      this.actStreamDataService.createEvent.eventSlug = result.data.eventSlug;
      this.actStreamDataService.createEvent.eventMembers = result.data.eventMembers;
      this.actStreamDataService.createEvent.eventStart = new Date(result.data.eventStart * 1000);
      this.actStreamDataService.createEvent.eventEnd = new Date(result.data.eventEnd * 1000);
      
      if (result.data.reminder.type !== null) {
        this.actStreamDataService.createEvent.reminderOpt = true;
        this.actStreamDataService.createEvent.reminder.type = result.data.reminder.type;
        this.actStreamDataService.createEvent.reminder.count = result.data.reminder.count;
      }

      this.actStreamDataService.createEvent.goingCount = this.actStreamDataService.createEvent.eventMembers.filter(
        file => file.eventResponse === 'going').length;
      this.actStreamDataService.createEvent.declineCount = this.actStreamDataService.createEvent.eventMembers.filter(
        file => file.eventResponse === 'decline').length;
      let selUser = this.actStreamDataService.createEvent.eventMembers.filter(
        file => file.eventUserSlug === this.cookieService.get('userSlug'))[0];
      if (selUser) {
        this.actStreamDataService.createEvent.loggedUser = this.cookieService.get('userSlug');
        this.actStreamDataService.createEvent.userResponse = true;
        this.actStreamDataService.createEvent.eventResponse = selUser.eventResponse;
        if (this.actStreamDataService.createEvent.eventResponse === '') {
          this.actStreamDataService.createEvent.eventResponse = 'going';
        }
      }
      else {
        this.actStreamDataService.createEvent.userResponse = false;
      }
      var strDate = new Date(this.actStreamDataService.createEvent.eventStart); //dd-mm-YYYY
      var endDate = new Date(this.actStreamDataService.createEvent.eventEnd); //dd-mm-YYYY
      if (strDate > new Date()) {
        this.actStreamDataService.createEvent.start = true;
      }
      if (strDate <= new Date() && endDate > new Date()) {
        this.actStreamDataService.createEvent.onGoing = true;
      }
      if (endDate < new Date()) {
        this.actStreamDataService.createEvent.end = true;
      }
      this.actStreamDataService.toUsers.toAllEmployee = this.actStreamDataService.createEvent.eventToAllEmployee;
      if (this.actStreamDataService.createEvent.eventToAllEmployee === true) {
        this.actStreamDataService.toUsers.toUsers = [];
      }
      else {
        this.actStreamDataService.toUsers.toUsers = [];
        for (let i = 0; i < this.actStreamDataService.createEvent.eventMembers.length; i++) {
          this.actStreamDataService.toUsers.toUsers.push({
            userSlug: this.actStreamDataService.createEvent.eventMembers[i].eventUserSlug,
            name: this.actStreamDataService.createEvent.eventMembers[i].eventUserName
          });
        }
      }
      this.spinner.hide();
      
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* get event details[end] */

  /* change event status[Start] */
  eventStatusUpdate(eventSlug, eventResponse) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.eventStatusUpdate(eventSlug, eventResponse).subscribe((result: any) => {
      this.fetchActivityStream();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
     },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* change event status[End] */

  /* get Event comments[Start] */
  getEventComments(index) {
    this.spinner.show();
     // Accessing activitystream API service
    return this.activityApiService.getEventCommentsAndResponses().subscribe((result: any) => {
      /* accessing message comments and reply count */
      this.actStreamDataService.fetchActivityStream.streamData[index].comments = result.data.eventComments;
      for (let i = 0; i < this.actStreamDataService.fetchActivityStream.streamData[index].comments.length; i++) {
        let replyCount = this.actStreamDataService.fetchActivityStream.streamData[index].comments.filter(
          file => file.eventParentCommentSlug === this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].eventCommentSlug).length;
        this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].replyCount = replyCount;
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /*  get Event comments[end] */

  /* event response[Start] */
  eventResponse(index) {
     // Accessing activitystream API service
    return this.activityApiService.eventResponse().subscribe((result: any) => {
       /* accessing message response list */
      this.actStreamDataService.fetchActivityStream.streamData[index].event.yourEventResponseSlug = result.data.eventResponseSlug;
      this.actStreamDataService.CommentsAndResponses.action = 'create';
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* event response[end] */

  /* add event comment[Start] */
  addEventComment(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.eventComment().subscribe((result: any) => {
      this.getEventComments(index);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
      this.actStreamDataService.CommentsAndResponses.commentTxt = '';
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* add event comment[end] */

  /* event comment response[Start] */
  eventCommentResponse(index) {
    return this.activityApiService.eventCommentResponse().subscribe((result: any) => {
      this.getEventComments(index);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* event comment response[end] */

  /* get comment reply[Start] */
  getEventCommentReply(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.getEventCommentsAndResponses().subscribe((result: any) => {
       /* accessing comments reply */
      for (let i = 0; i < result.data.eventComments.length; i++) {
        let Reply = result.data.eventComments.filter(
          file => file.eventParentCommentSlug === comment.eventCommentSlug)[i];
        if (Reply) {
          this.actStreamDataService.CommentsAndResponses.getCommentReply.push(Reply);
        }
      }
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].reply = this.actStreamDataService.CommentsAndResponses.getCommentReply;
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].replyCount = this.actStreamDataService.CommentsAndResponses.getCommentReply.length;
      this.actStreamDataService.CommentsAndResponses.getCommentReply = [];
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* get comment reply[end] */

  /* add event comment reply[Start] */
  addEventCommentReplay(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.eventComment().subscribe((result: any) => {
      this.getEventCommentReply(comment, index, idx);
      this.actStreamDataService.CommentsAndResponses.commentSlug = null;
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
      this.actStreamDataService.CommentsAndResponses.commentTxt = '';
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* add event comment reply[end] */

  /* delete event comment[Start] */
  deleteEventComment(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.eventComment().subscribe((result: any) => {
      this.getEventComments(index);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = null;
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* delete event comment[end] */

   /* Sandbox to handle API call for delete Event comment reply[Start] */
   deleteEventCommentReplay(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.eventComment().subscribe((result: any) => {
      this.getEventCommentReply(comment, index, idx);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.resetPopUpBox();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for delete Event comment reply[end] */

   /* Sandbox to handle API call for get poll comment response[Start] */
   getEventCommentResponse(comment, index, idx) {
    return this.activityApiService.appreciationCommentResponse().subscribe((result: any) => {
      this.getEventCommentReply(comment, index, idx);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get poll comment response[end] */
  /*******************   Sandbox to handle API call for event management  ******************/


  /*******************   Sandbox to handle API call for announcement management  ******************/
  /*  Creating the Announcement[Start] */
  createNewAnnouncement() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.createNewAnnouncement().subscribe((result: any) => {
      this.resetData();
      this.fetchActivityStream();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /*  Creating the Announcement[End] */

  /* change announcement read status[Start] */
  makeHasRead(ancSlug) {
    this.spinner.show();
    return this.activityApiService.makeHasRead(ancSlug).subscribe((result: any) => {
      this.fetchActivityStream();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* change announcement read status[End] */

  /* get announcement response[Start] */
  getAnnouncementResponses(index) {
    this.spinner.show();
    return this.activityApiService.getAnnouncementCommentsAndResponses().subscribe((result: any) => {
      this.actStreamDataService.fetchActivityStream.streamData[index].response = result.data.announcementResponses;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* get announcement response[End] */

  /*  get announcement comments[Start] */
  getAnnouncementComments(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.getAnnouncementCommentsAndResponses().subscribe((result: any) => {
      /* accessing announcement comments and reply count */
      this.actStreamDataService.fetchActivityStream.streamData[index].comments = result.data.announcementComments;
      for (let i = 0; i < this.actStreamDataService.fetchActivityStream.streamData[index].comments.length; i++) {
        let replyCount = this.actStreamDataService.fetchActivityStream.streamData[index].comments.filter(
          file => file.ancParentCommentSlug === this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].ancCommentSlug).length;
        this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].replyCount = replyCount;
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* get announcement comments[end] */

  /* announcement response[start] */
  announcementResponse(index) {
    // Accessing activitystream API service
    return this.activityApiService.announcementResponse().subscribe((result: any) => {
      /* accessing announcement response list */
      this.actStreamDataService.fetchActivityStream.streamData[index].announcement.yourAnnouncementResponseSlug = result.data.ancResponseSlug;
      this.actStreamDataService.CommentsAndResponses.action = 'create';
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /*  announcement response[end] */

  /* add announcement comment[start] */
  addAnnouncementComment(index) {
     this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.announcementComment().subscribe((result: any) => {
      this.getAnnouncementComments(index);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
      this.actStreamDataService.CommentsAndResponses.commentTxt = '';
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* add announcement comment[end] */

  /* delete announcement comment[start] */
  deleteAnnouncementComment(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.announcementComment().subscribe((result: any) => {
      this.getAnnouncementComments(index);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = null;
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* delete announcement comment[end] */

  /* get announcemet comment reply[Start] */
  getAnnouncemetnCommentReply(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.getAnnouncementCommentsAndResponses().subscribe((result: any) => {
       /* accessing comments reply */
      for (let i = 0; i < result.data.announcementComments.length; i++) {
        let Reply = result.data.announcementComments.filter(
          file => file.ancParentCommentSlug === comment.ancCommentSlug)[i];
        if (Reply) {
          this.actStreamDataService.CommentsAndResponses.getCommentReply.push(Reply);
        }
      }
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].reply = this.actStreamDataService.CommentsAndResponses.getCommentReply;
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].replyCount = this.actStreamDataService.CommentsAndResponses.getCommentReply.length;
      this.actStreamDataService.CommentsAndResponses.getCommentReply = [];
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* get announcemet comment reply[end] */

  /* add announcemet comment reply[Start] */
  addAnnouncemetCommentReplay(comment, index, idx) {
    return this.activityApiService.announcementComment().subscribe((result: any) => {
      this.getAnnouncemetnCommentReply(comment, index, idx);
      this.actStreamDataService.CommentsAndResponses.commentSlug = null;
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.parentCommentSlug = '';
      this.actStreamDataService.CommentsAndResponses.commentTxt = '';
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* add announcemet comment reply[end] */

  /* Sandbox to handle API call for get announcement comment response[Start] */
  getAnnouncementCommentResponse(comment, index, idx) {
    return this.activityApiService.announcementCommentResponse().subscribe((result: any) => {
      this.getAnnouncemetnCommentReply(comment, index, idx);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get announcement comment response[end] */

  /* Sandbox to handle API call for delete announcemet comment reply[Start] */
  deleteAnnouncementCommentReplay(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.announcementComment().subscribe((result: any) => {
      this.getAnnouncemetnCommentReply(comment, index, idx);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.resetPopUpBox();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for delete announcemet comment reply[end] */

  /* Sandbox to handle API call for add announcement comment response[start] */
  announcementCommentResponse(index) {
    return this.activityApiService.announcementCommentResponse().subscribe((result: any) => {
      this.getAnnouncementComments(index);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for delete announcement comment response[end] */
  /*******************   Sandbox to handle API call for announcement management  ******************/

  /*******************   Sandbox to handle API call for poll management  ******************/
  /* Sandbox to handle API call for Creating the Poll[Start] */
  createNewPoll() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.createNewPoll().subscribe((result: any) => {
      this.resetData();
      this.fetchActivityStream();
      this.actStreamDataService.createPoll.pollQuestions = [];
      this.actStreamDataService.createPoll.pollQuestions.push({
        action: 'create', pollQuestionId: null, pollQuestion: '',
        allowMultipleChoice: false, answerOptions: [{
          pollOptId: null, pollOption: ''
        }, {
          pollOptId: null, pollOption: ''
        }]
      })
      for (let i = 0; i < this.actStreamDataService.createPoll.pollQuestions.length; i++) {
        this.actStreamDataService.createPoll.pollQuestions[i].pollQuestion = '';
        for (let x = 0; x < this.actStreamDataService.createPoll.pollQuestions[i].answerOptions.length; x++) {
          this.actStreamDataService.createPoll.pollQuestions[i].answerOptions[x].pollOption = '';
        }
      }
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for Creating the Poll[End] */

  /* Sandbox to handle API call for submit Vote Poll[Start] */
  submitVote() {
    this.spinner.show();
    return this.activityApiService.submitVote().subscribe((result: any) => {
      this.fetchActivityStream();
      this.toastService.Success(result.data.msg);
      this.spinner.hide();
    },
      error => {
        console.log(error.msg);
        this.spinner.hide();
        this.toastService.Error(error.msg);
      })
  }
  /* Sandbox to handle API call for submit Vote Poll[End] */

  /* Sandbox to handle API call for close  Vote Poll[Start] */
  closeVote() {
    this.spinner.show();
    return this.activityApiService.closeVote().subscribe((result: any) => {
      this.fetchActivityStream();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for close Vote Poll[End] */

  /* Sandbox to handle API call for get Message Comments[Start] */
  getPollComments(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.getPollCommentsAndResponses().subscribe((result: any) => {
      this.actStreamDataService.fetchActivityStream.streamData[index].comments = result.data.pollComments;
      for (let i = 0; i < this.actStreamDataService.fetchActivityStream.streamData[index].comments.length; i++) {
        let replyCount = this.actStreamDataService.fetchActivityStream.streamData[index].comments.filter(
          file => file.pollParentCommentSlug === this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].pollCommentSlug).length;
        this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].replyCount = replyCount;
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get poll Comments[end] */

  /* Sandbox to handle API call for add poll comment[Start] */
  addPollComment(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.pollComment().subscribe((result: any) => {
      this.getPollComments(index);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.parentCommentSlug = '';
      this.actStreamDataService.CommentsAndResponses.commentTxt = '';
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for add poll comment[end] */

  /* Sandbox to handle API call for add poll comment reply[Start] */
  addPollCommentReplay(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.pollComment().subscribe((result: any) => {
      this.getPollCommentReply(comment, index, idx);
      this.actStreamDataService.CommentsAndResponses.commentSlug = null;
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.parentCommentSlug = '';
      this.actStreamDataService.CommentsAndResponses.commentTxt = '';
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for add poll comment reply[end] */

  /* Sandbox to handle API call for add poll response[Start] */
  pollResponse(index) {
    return this.activityApiService.pollResponse().subscribe((result: any) => {
      this.actStreamDataService.fetchActivityStream.streamData[index].poll.yourPollResponseSlug = result.data.pollResponseSlug;
      this.actStreamDataService.CommentsAndResponses.action = 'create';
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for add poll response[end] */

  /* Sandbox to handle API call for get poll comment response[Start] */
  pollCommentResponse(index) {
    return this.activityApiService.pollCommentResponse().subscribe((result: any) => {
      this.getPollComments(index);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get poll comment response[end] */

  /* Sandbox to handle API call for get poll Comments[Start] */
  getPollCommentReply(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.getPollCommentsAndResponses().subscribe((result: any) => {
         /* accessing comments reply */
      for (let i = 0; i < result.data.pollComments.length; i++) {
        let Reply = result.data.pollComments.filter(
          file => file.pollParentCommentSlug === comment.pollCommentSlug)[i];
        if (Reply) {
          this.actStreamDataService.CommentsAndResponses.getCommentReply.push(Reply);
        }
      }
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].reply = this.actStreamDataService.CommentsAndResponses.getCommentReply;
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].replyCount = this.actStreamDataService.CommentsAndResponses.getCommentReply.length;
      this.actStreamDataService.CommentsAndResponses.getCommentReply = [];
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get poll Comments[end] */

  /* Sandbox to handle API call for delete poll comment[Start] */
  deletePollComment(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.pollComment().subscribe((result: any) => {
      this.getPollComments(index);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = '';
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for delete poll comment[end] */

  /* Sandbox to handle API call for delete poll comment reply[Start] */
  deletePollCommentReplay(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.pollComment().subscribe((result: any) => {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.resetPopUpBox();
      this.getPollCommentReply(comment, index, idx);
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for delete poll comment reply[end] */

   /* Sandbox to handle API call for get poll comment response[Start] */
   getPollCommentResponse(comment, index, idx) {
    return this.activityApiService.pollCommentResponse().subscribe((result: any) => {
      this.getPollCommentReply(comment, index, idx);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get poll comment response[end] */
  /*******************   Sandbox to handle API call for poll management  ******************/


  /*******************   Sandbox to handle API call for appreciation management  ******************/
  /* Creating new Appreciation[Start] */
  createNewAppreciation() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.createNewAppreciation().subscribe((result: any) => {
      this.resetData();
      this.fetchActivityStream();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Creating new Appreciation[end] */

  /* get appreciation comments[Start] */
  getAppreciationComments(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.getAppreciationCommentsAndResponses().subscribe((result: any) => {
      /* accessing message comments and reply count */
      this.actStreamDataService.fetchActivityStream.streamData[index].comments = result.data.appreciationComments;
      for (let i = 0; i < this.actStreamDataService.fetchActivityStream.streamData[index].comments.length; i++) {
        let replyCount = this.actStreamDataService.fetchActivityStream.streamData[index].comments.filter(
          file => file.aprParentCommentSlug === this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].aprCommentSlug).length;
        this.actStreamDataService.fetchActivityStream.streamData[index].comments[i].replyCount = replyCount;
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* get appreciation comments[end] */

  /* appreciation response[Start] */
  appreciationResponse(index) {
     // Accessing activitystream API service
    return this.activityApiService.appreciationResponse().subscribe((result: any) => {
      /* accessing message response list */
      this.actStreamDataService.fetchActivityStream.streamData[index].appreciation.yourAprResponseSlug = result.data.aprResponseSlug;
      this.actStreamDataService.CommentsAndResponses.action = 'create';
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* appreciation response[end] */

  /* add Appreciation comment[Start] */
  addAppreciationComment(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.appreciationComment().subscribe((result: any) => {
      this.getAppreciationComments(index);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
      this.actStreamDataService.CommentsAndResponses.commentTxt = '';
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* add Appreciation comment[end] */

  /* Appreciation comment response[Start] */
  appreciationCommentResponse(index) {
    return this.activityApiService.appreciationCommentResponse().subscribe((result: any) => {
      this.getAppreciationComments(index);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Appreciation comment response[end] */

  /* delete Appreciation comment[Start] */
  deleteAppreciationComment(index) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.appreciationComment().subscribe((result: any) => {
      this.getAppreciationComments(index);
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = null;
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* delete Appreciation comment[end] */

  /* get appriciation comment reply[Start] */
  getAppreciationCommentReply(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.getAppreciationCommentsAndResponses().subscribe((result: any) => {
       /* accessing comments reply */
      for (let i = 0; i < result.data.appreciationComments.length; i++) {
        let Reply = result.data.appreciationComments.filter(
          file => file.aprParentCommentSlug === comment.aprCommentSlug)[i];
        if (Reply) {
          this.actStreamDataService.CommentsAndResponses.getCommentReply.push(Reply);
        }
      }
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].reply = this.actStreamDataService.CommentsAndResponses.getCommentReply;
      this.actStreamDataService.fetchActivityStream.streamData[index].comments[idx].replyCount = this.actStreamDataService.CommentsAndResponses.getCommentReply.length;
      this.actStreamDataService.CommentsAndResponses.getCommentReply = [];
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.toastService.Error(err.msg);
      })
  }
  /* get appriciation comment reply[end] */

  /* add appreciation comment reply[Start] */
  addAppreciationCommentReplay(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.appreciationComment().subscribe((result: any) => {
      this.getAppreciationCommentReply(comment, index, idx);
      this.actStreamDataService.CommentsAndResponses.commentSlug = null;
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
      this.actStreamDataService.CommentsAndResponses.commentTxt = '';
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* add appreciation comment reply[end] */

  /* delete appreciation comment reply[Start] */
  deleteAppreciationCommentReplay(comment, index, idx) {
    this.spinner.show();
    // Accessing activitystream API service
    return this.activityApiService.appreciationComment().subscribe((result: any) => {
      this.getAppreciationCommentReply(comment, index, idx)
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.resetPopUpBox();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* delete appreciation comment reply[end] */

   /* Sandbox to handle API call for get announcement comment response[Start] */
   getAppreciationCommentResponse(comment, index, idx) {
    return this.activityApiService.appreciationCommentResponse().subscribe((result: any) => {
      this.getAppreciationCommentReply(comment, index, idx);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for get announcement comment response[end] */

  /* Sandbox to handle API call for managing task status[Start] */
  manageTaskStatus() {
    this.spinner.show();
    // Accessing task API service
    return this.activityApiService.completeTask().subscribe((result: any) => {
       this.getTaskWidgetDetails();
        this.spinner.hide();
      this.actStreamDataService.resetTaskRunManagement();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for managing task status[End] */

  /****  Sandbox to handle API call for appreciation management *****/
  /*******************   Sandbox to handle API call for appreciation management  ******************/
}
