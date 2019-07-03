import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../activity.sandbox';
import { CookieService } from 'ngx-cookie-service';
import { TaskDataService } from '../../shared/services/task-data.service';
import { Router, ActivatedRoute } from '@angular/router';
import { TaskSandbox } from '../../task/task.sandbox';

@Component({
  selector: 'app-sec-task',
  templateUrl: './sec-task.component.html',
  styleUrls: ['./sec-task.component.scss']
})
export class SecTaskComponent implements OnInit {
  @Input() data: any;
  @Input() index: number;
  idx: any;
  responseCount: any;
  commentCount: any;
  loggedUser = '';
  replyTxt: any;
  commentTxt: any;
  commentCreatorUserName: '';
  list = false;
  replaylist = false;
  listComment = false;
  public assetUrl = Configs.assetBaseUrl;

  constructor(
    public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService,
    private cookieService: CookieService,
    public taskDataService: TaskDataService,
    private router: Router,
    private route: ActivatedRoute,
    private taskSandbox: TaskSandbox,) { }

  ngOnInit() {
    this.commentCount = this.data.task.taskCommentsCount;
    this.loggedUser = this.cookieService.get('userSlug');
    this.actStreamDataService.comments[this.index] = false;
    this.actStreamDataService.resetPopUpBox();
  }

  /* delete selected task [Start] */
  deleteTask() {
    this.actStreamDataService.optionBtn[this.index] = false;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected Task?'
    this.actStreamDataService.deletePopUp[this.index] = true;
  }
  conformDeleteTask() {
    this.actStreamDataService.createTask.action = 'delete';
    this.actStreamDataService.createTask.taskSlug = this.data.task.taskSlug;
    this.activitySandboxService.deleteTask();
  }
  cancelDelete() {
    this.actStreamDataService.resetPopUpBox();
  }
  /* delete selected task [end] */

  /* update task [Start] */
  updateTask() {
    this.actStreamDataService.createMessage.action = 'update';
    this.actStreamDataService.selectedWidget.selWctab = 'task';
    this.actStreamDataService.activityCreatePopUp.show = true;
    this.actStreamDataService.createTask.taskSlug = this.data.task.taskSlug;
    this.activitySandboxService.fechTaskDetails();
    this.actStreamDataService.toUsers.toUsers = [];
    this.actStreamDataService.createTask.responsiblePerson = {
      responsiblePersonId: this.data.task.taskResponsiblePersonSlug,
      responsiblePersonName: this.data.task.taskResponsiblePersonName
    };
    for (let i = 0; i < this.data.task.toUsers.length; i++) {
      this.actStreamDataService.toUsers.toUsers.push({
        userSlug: this.data.task.toUsers[i].userSlug,
        name: this.data.task.toUsers[i].userName
      });
    }
    this.actStreamDataService.createTask.endDate = new Date(this.data.task.taskEndDate * 1000);
    this.actStreamDataService.createTask.title = this.data.task.taskTitle;
    this.actStreamDataService.msgEditor.text = this.data.task.taskDesc;
  }
  /* update task [end] */

  /* get task comments [Start] */
  getComments() {
    this.actStreamDataService.createTask.taskSlug = this.data.task.taskSlug;
    this.activitySandboxService.getTaskComments(this.index);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.comments[this.index] = !this.actStreamDataService.comments[this.index];
  }
  /* get task comments [end] */

  /* get comment Reply [start] */
  getCommentReply(comment, idx) {
    this.replyTxt = '';
    this.actStreamDataService.CommentsAndResponses.commentSlug = null;
    this.actStreamDataService.createTask.taskSlug = this.data.task.taskSlug;
    this.activitySandboxService.getTaskCommentReply(comment, this.index, idx);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.reply[idx] = !this.actStreamDataService.reply[idx];
  }
  /* get comment Reply [end] */

  /* add task comment[start] */
  addComment() {
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.createTask.taskSlug = this.data.task.taskSlug;
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
    this.activitySandboxService.addtaskComment(this.index);
    this.commentTxt = '';
    this.list = false;
    if (this.actStreamDataService.CommentsAndResponses.action === 'create') {
      this.commentCount = this.commentCount + 1;
      }
      else {
        this.commentCount = this.commentCount;
      }
      this.actStreamDataService.resetPopUpBox();
  }
  /* add task comment[end] */

  /* add comment reply [start] */
  addCommentReply(replyTxt, comment, idx) {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = comment.commentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = replyTxt;
    this.actStreamDataService.createTask.taskSlug = this.data.task.taskSlug;
    this.activitySandboxService.addTaskCommentReplay(comment, this.index, idx);
    comment.replyTxt = '';
    this.replaylist = false;
  }
  /* add comment reply [start] */

  /* add task comment response[start] */
  getResponseForComment(comment) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.commentSlug;
    if (comment.like.length === 0) {
      this.actStreamDataService.CommentsAndResponses.taskCommentLike = true;
    }
    else {
      if (comment.like.meLiked === false) {
        this.actStreamDataService.CommentsAndResponses.taskCommentLike = true;
      }
      else {
        this.actStreamDataService.CommentsAndResponses.taskCommentLike = false;
      }
    }
    this.activitySandboxService.addtaskCommentResponse(this.index);
  }
  /* add task comment response[end] */


  /* delete task comment[start] */
  deleteComment(idx) {
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected Comment?';
    this.actStreamDataService.deleteCommentPopUp[idx] = true;
  }
  conformDeleteComment(comment) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.commentSlug;
    this.actStreamDataService.CommentsAndResponses.action = 'delete';
    this.actStreamDataService.createTask.taskSlug = this.data.task.taskSlug;
    this.activitySandboxService.deleteTaskComment(this.index);
    this.commentCount = this.commentCount - 1;
    this.actStreamDataService.resetPopUpBox();
    this.commentTxt = '';
  }
  /* delete task comment[end] */

  /* update task comment[start] */
  updateComment(comment) {
    this.commentTxt = comment.description;
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.commentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.CommentsAndResponses.action = 'update';
    this.actStreamDataService.createTask.taskSlug = this.data.task.taskSlug;
  }
  /* update task comment[end] */

  /* response comment reply [Start] */
  replyResponse(reply, comment, idx) {
    if (reply.like.length === 0) {
      this.actStreamDataService.CommentsAndResponses.taskCommentLike = true;
      this.actStreamDataService.CommentsAndResponses.commentSlug = reply.commentSlug;
      reply.like.meLiked = true;
    }
    else {
      if (reply.like.meLiked === true) {
        this.actStreamDataService.CommentsAndResponses.taskCommentLike = false;
        this.actStreamDataService.CommentsAndResponses.commentSlug = reply.commentSlug;
        reply.like.meLiked = false;
      }
      else {
        this.actStreamDataService.CommentsAndResponses.taskCommentLike = true;
        this.actStreamDataService.CommentsAndResponses.commentSlug = reply.commentSlug;
        reply.like.meLiked = true;
      }
    }
    this.activitySandboxService.getTaskCommentResponse(comment, this.index, idx);
  }
  /* response comment reply [end] */

  /* add comment reply [Start] */
  replyToReply(reply, comment) {
    this.commentCreatorUserName = reply.commentedUserName;
    comment.replyTxt = '@' + this.commentCreatorUserName + ' ';
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = reply.commentSlug;
  }
  /* add comment reply [end] */

  /* delete comment reply [Start] */
  deleteReply(reply, index) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = reply.commentSlug;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected reply?'
    this.actStreamDataService.deleteReplyPopUp[index] = true;
  }
  conformDeleteReply(comment, idx) {
    this.actStreamDataService.createTask.taskSlug = this.data.task.taskSlug;
    this.activitySandboxService.deleteTaskCommentReplay(comment, this.index, idx);
  }
  /* delete comment reply [end] */

  viewTaskDetails(taskSlug): void {
    this.taskDataService.taskDetails.selectedTask = taskSlug;
    this.taskSandbox.fetchSelectedTaskDetails();
    this.router.navigateByUrl('/authorized/task/task-d/(task-overview//detailpopup:task-detail/'+ taskSlug);
}

showList() {
  var result = this.commentTxt.substr(this.commentTxt.length - 1);
  if (result === '@') {
    this.list = true;
    this.actStreamDataService.responsiblePersons.searchParticipantsTxt = '';
    this.activitySandboxService.getReposiblePerson();
  }
  else{
    let x = this.commentTxt.split("@");
    let txt = x.length - 1;
    this.actStreamDataService.responsiblePersons.searchParticipantsTxt = x[txt];
    this.activitySandboxService.getReposiblePerson();
  }
}

hideList() {
  this.list = false;
}
hideReplayList() {
  this.replaylist = false;
}
showListComment() {
  this.listComment = !this.listComment;
}
hideListComment() {
  this.listComment = false;
}
selectUser(user) {
  let stringToSplit = this.commentTxt;
  stringToSplit.split("@");
  let myString = stringToSplit.substring(0, stringToSplit.lastIndexOf("@"));
  this.commentTxt = myString + '@' + user.employee_name + ' ';
  this.list = false;
}

selectReplayUser(user, comment) {
  let stringToSplit = comment.replyTxt;
  stringToSplit.split("@");
  let myString = stringToSplit.substring(0, stringToSplit.lastIndexOf("@"));
  comment.replyTxt = myString + '@' + user.employee_name + ' ';
  this.replaylist = false;
}

showReplayList(replyTxt) {
  var result = replyTxt.substr(replyTxt.length - 1);
  if (result === '@') {
    this.replaylist = true;
    this.actStreamDataService.responsiblePersons.searchParticipantsTxt = '';
    this.activitySandboxService.getReposiblePerson();
  }
  else{
    let x = replyTxt.split("@");
    let txt = x.length - 1;
    this.actStreamDataService.responsiblePersons.searchParticipantsTxt = x[txt];
    this.activitySandboxService.getReposiblePerson();
  }
}
}

