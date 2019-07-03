import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service'
import { ActivitySandboxService } from '../activity.sandbox';
import { CookieService } from 'ngx-cookie-service';
 
@Component({
  selector: 'app-sec-message',
  templateUrl: './sec-message.component.html',
  styleUrls: ['./sec-message.component.scss']
})
export class SecMessageComponent implements OnInit {
  @Input() data: any;
  @Input() index: number;
  responseCount: any;
  commentCount: any;
  loggedUser = '';
  commentTxt: any;
  replyTxt: any;
  commentCreatorUserName: '';
  list = false;
  replaylist = false;
  listComment = false;
  public assetUrl = Configs.assetBaseUrl;

  constructor(
    public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService,
    private cookieService: CookieService) { }

  ngOnInit() {
    this.commentCount = this.data.message.messageCommentsCount;
    this.responseCount = this.data.message.messageResponsesCount;
    this.loggedUser = this.cookieService.get('userSlug');
    this.actStreamDataService.comments[this.index] = false;
    this.actStreamDataService.resetPopUpBox();
  }

  /* delete selected message [Start] */
  deleteMessage() {
    this.actStreamDataService.optionBtn[this.index] = false;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected message?'
    this.actStreamDataService.deletePopUp[this.index] = true;
  }
  conformDeleteMessage() {
    this.actStreamDataService.createMessage.action = 'delete';
    this.actStreamDataService.createMessage.msgSlug = this.data.message.msgSlug;
    this.activitySandboxService.createNewMessage();
  }
  cancelDelete() {
    this.actStreamDataService.resetPopUpBox();
  }
  /* delete selected message [end] */

  /* update message [Start] */
  updateMessage() {
    this.actStreamDataService.createMessage.action = 'update';
    this.actStreamDataService.selectedWidget.selWctab = 'message';
    this.actStreamDataService.activityCreatePopUp.show = true;
    this.actStreamDataService.toUsers.toUsers = [];
    for (let i = 0; i < this.data.message.toUsers.length; i++) {
      this.actStreamDataService.toUsers.toUsers.push({
        userSlug: this.data.message.toUsers[i].userSlug,
        name: this.data.message.toUsers[i].userName
      });
    }
    this.actStreamDataService.createMessage.msgSlug = this.data.message.msgSlug;
    this.actStreamDataService.createMessage.msgTitle = this.data.message.msgTitle;
    this.actStreamDataService.msgEditor.text = this.data.message.msgDesc;
    this.actStreamDataService.toUsers.toAllEmployee = this.data.message.toAllEmployee;
  }
  /* update message [end] */

  /* get message response [Start] */
  messageResponse() {
    if (this.data.message.yourMessageResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.responseSlug = null;
      this.data.message.yourMessageResponse = 'like';
      this.responseCount = this.responseCount + 1;
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.responseSlug = this.data.message.yourMessageResponseSlug;
      this.data.message.yourMessageResponse = '';
      this.responseCount = this.responseCount - 1;
    }
    this.actStreamDataService.createMessage.msgSlug = this.data.message.msgSlug;
    this.activitySandboxService.messageResponse(this.index);
  }
  /* get message response [end] */

  /* get message comments [Start] */
  getMessageComments() {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
    this.actStreamDataService.createMessage.msgSlug = this.data.message.msgSlug;
    this.activitySandboxService.getMessageComments(this.index);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.comments[this.index] = !this.actStreamDataService.comments[this.index];
  }
  /* get message comments [end] */

  /* get comment Reply [start] */
  getCommentReply(comment, idx) {
    this.replyTxt = '';
    this.actStreamDataService.CommentsAndResponses.commentSlug = null;
    this.actStreamDataService.createMessage.msgSlug = this.data.message.msgSlug;
    this.activitySandboxService.getMessageCommentReply(comment, this.index, idx);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.reply[idx] = !this.actStreamDataService.reply[idx];
  }
  /* get comment Reply [end] */

  /* add message comment[start] */
  addMessageComment() {
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.createMessage.msgSlug = this.data.message.msgSlug;
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
    this.activitySandboxService.addMessageComment(this.index);
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
  /* add message comment [end] */

  /* add comment reply [start] */
  addCommentReply(replyTxt, comment, idx) {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = comment.msgCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = replyTxt;
    this.actStreamDataService.createMessage.msgSlug = this.data.message.msgSlug;
    this.activitySandboxService.addMessageCommentReplay(comment, this.index, idx);
    comment.replyTxt = '';
    this.replaylist = false;

  }
  /* add comment reply [start] */

  /* add message comment response[start] */
  getResponseForComment(comment, idx) {
    if (comment.yourCommentResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = comment.msgCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = null;
      comment.yourCommentResponse = 'like';
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.commentSlug = comment.msgCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = comment.yourCommentResponseSlug;
      comment.yourCommentResponse = '';
    }
    this.actStreamDataService.reply[idx] = false;
    this.activitySandboxService.getMessageCommentResponse(this.index);
  }
  /* add message comment response[end] */

  /* delete message comment[start] */
  deleteComment(idx) {
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected Comment?';
    this.actStreamDataService.deleteCommentPopUp[idx] = true;
  }
  conformDeleteComment(comment) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.msgCommentSlug;
    this.actStreamDataService.CommentsAndResponses.action = 'delete';
    this.actStreamDataService.createMessage.msgSlug = this.data.message.msgSlug;
    this.activitySandboxService.deleteMessageComment(this.index);
    this.commentCount = this.commentCount - 1;
    this.actStreamDataService.resetPopUpBox();
    this.commentTxt = '';
  }
  /* delete message comment[end] */

  /* update message comment[start] */
  updateComment(comment) {
    this.commentTxt = comment.comment;
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.msgCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.CommentsAndResponses.action = 'update';
    this.actStreamDataService.createMessage.msgSlug = this.data.message.msgSlug;
  }
  /* update message comment[end] */

  /* delete comment reply [Start] */
  deleteReply(reply, index) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = reply.msgCommentSlug;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected reply?'
    this.actStreamDataService.deleteReplyPopUp[index] = true;
  }
  conformDeleteReply(comment, idx) {
    this.actStreamDataService.CommentsAndResponses.action = 'delete';
    this.actStreamDataService.createMessage.msgSlug = this.data.message.msgSlug;
    this.activitySandboxService.deleteMessageCommentReplay(comment, this.index, idx);
  }
  /* delete comment reply [end] */

  /* update comment reply[start] */
  updateCommentReply(reply,comment) {
    comment.replyTxt = reply.comment;
    this.actStreamDataService.CommentsAndResponses.commentSlug = reply.msgCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = reply.replyTxt;
    this.actStreamDataService.CommentsAndResponses.action = 'update';
    this.actStreamDataService.createMessage.msgSlug = this.data.message.msgSlug;
  }
  /* update comment reply[end] */

  /* add reply for reply[start] */
  replyToReply(reply, comment) {
    this.commentCreatorUserName = reply.commentCreatorUserName;
    comment.replyTxt = '@' + this.commentCreatorUserName + ' ';
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = reply.commentSlug;
  }
  /* add reply for reply[end] */

  /* reply response[start] */
  replyResponse(reply, comment, idx) {
    if (reply.yourCommentResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = reply.msgCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = null;
      reply.yourCommentResponse = 'like';
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.commentSlug = reply.msgCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = reply.yourCommentResponseSlug;
      reply.yourCommentResponse = '';
    }
    this.activitySandboxService.getReplyCommentResponse(comment, this.index, idx);
  }
  /* reply response[end] */
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