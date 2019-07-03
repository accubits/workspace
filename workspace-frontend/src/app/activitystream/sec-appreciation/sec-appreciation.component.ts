import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service'
import { ActivitySandboxService } from '../activity.sandbox';
import { CookieService } from 'ngx-cookie-service';
 
@Component({
  selector: 'app-sec-appreciation',
  templateUrl: './sec-appreciation.component.html',
  styleUrls: ['./sec-appreciation.component.scss']
})

export class SecAppreciationComponent implements OnInit {
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

  constructor(public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService, 
    private cookieService: CookieService) { }

  ngOnInit() {
    this.responseCount = this.data.appreciation.aprResponsesCount;
    this.commentCount = this.data.appreciation.aprCommentsCount;
    this.loggedUser = this.cookieService.get('userSlug');
    this.actStreamDataService.comments[this.index] = false;
    this.actStreamDataService.resetPopUpBox();
  }

  /* delete selected Appreciation [Start] */
  deleteAppreciation() {
    this.actStreamDataService.optionBtn[this.index] = false;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected Appreciation?'
    this.actStreamDataService.deletePopUp[this.index] = true;
  }
  conformAppreciationDelete() {
    this.actStreamDataService.createAppreciation.action = 'delete';
    this.actStreamDataService.createAppreciation.aprSlug = this.data.appreciation.aprSlug;
    this.activitySandboxService.createNewAppreciation();
  }
  cancelDelete() {
    this.actStreamDataService.resetPopUpBox();
  }
  /* delete selected Appreciation [end] */

  /* update Appreciation [Start] */
  updateAppreciation() {
    this.actStreamDataService.createAppreciation.action = 'update';
    this.actStreamDataService.selectedWidget.selWctab = 'appreciation';
    this.actStreamDataService.activityCreatePopUp.show = true;
    this.actStreamDataService.createAppreciation.aprHasDisplayDuration = this.data.appreciation.aprHasDisplayDuration;
    if (this.data.appreciation.aprHasDisplayDuration) {
      this.actStreamDataService.addDisplayPeriod.show = true;
    }
    else {
      this.actStreamDataService.addDisplayPeriod.show = false;
    }
    if(this.data.appreciation.aprDisplayStart === null){
      this.actStreamDataService.createAppreciation.aprDisplayStart = ''
    }
    else{
       this.actStreamDataService.createAppreciation.aprDisplayStart = new Date(this.data.appreciation.aprDisplayStart * 1000);
    }
    if(this.data.appreciation.aprDisplayEnd === null){
      this.actStreamDataService.createAppreciation.aprDisplayEnd = ''
    }
    else{
      this.actStreamDataService.createAppreciation.aprDisplayEnd = new Date(this.data.appreciation.aprDisplayEnd * 1000);
    }

    this.actStreamDataService.toUsers.toUsers = [];
    for (let i = 0; i < this.data.appreciation.toUsers.length; i++) {
      this.actStreamDataService.toUsers.toUsers.push({
        userSlug: this.data.appreciation.toUsers[i].userSlug,
        name: this.data.appreciation.toUsers[i].userName
      });
    }
    this.actStreamDataService.createAppreciation.recipients = [];
    for (let i = 0; i < this.data.appreciation.recipients.length; i++) {
      this.actStreamDataService.createAppreciation.recipients.push({
        userSlug: this.data.appreciation.recipients[i].userSlug,
        name: this.data.appreciation.recipients[i].userName
      });
    }
    this.actStreamDataService.createAppreciation.aprSlug = this.data.appreciation.aprSlug;
    this.actStreamDataService.createAppreciation.aprTitle = this.data.appreciation.aprTitle;
    this.actStreamDataService.createAppreciation.aprDesc = this.data.appreciation.aprDesc;
    this.actStreamDataService.msgEditor.text = this.data.appreciation.aprDesc;
    this.actStreamDataService.toUsers.toAllEmployee = this.data.appreciation.toAllEmployee;
  }
  /* update Appreciation [end] */

  /* appreciation response [Start] */
  appreciationResponse() {
    if (this.data.appreciation.yourAprResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.responseSlug = null;
      this.data.appreciation.yourAprResponse = 'like';
      this.responseCount = this.responseCount + 1;
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.responseSlug = this.data.appreciation.yourAprResponseSlug;
      this.data.appreciation.yourAprResponse = '';
      this.responseCount = this.responseCount - 1;
    }
    this.actStreamDataService.createAppreciation.aprSlug = this.data.appreciation.aprSlug;
    this.activitySandboxService.appreciationResponse(this.index);
  }
  /* get Appreciation response [end] */

  /* get Appreciation comments [Start] */
  getAppreciationComments() {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
    this.actStreamDataService.createAppreciation.aprSlug = this.data.appreciation.aprSlug;
    this.activitySandboxService.getAppreciationComments(this.index);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.comments[this.index] = !this.actStreamDataService.comments[this.index];
  }
  /* get Appreciation comments [end] */

  /* add Appreciation comment[start] */
  addAppreciationComment() {
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.createAppreciation.aprSlug = this.data.appreciation.aprSlug;
    this.activitySandboxService.addAppreciationComment(this.index);
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
  /* add Appreciation comment [end] */

  /* get comment Reply [start] */
  getCommentReply(comment, idx) {
    this.replyTxt = '';
    this.actStreamDataService.CommentsAndResponses.commentSlug = null;
    this.actStreamDataService.createAppreciation.aprSlug = this.data.appreciation.aprSlug;
    this.activitySandboxService.getAppreciationCommentReply(comment, this.index, idx);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.reply[idx] = !this.actStreamDataService.reply[idx];
  }
  /* get comment Reply [end] */

   /* add comment reply [start] */
   addCommentReply(replyTxt, comment, idx) {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = comment.aprCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = replyTxt;
    this.actStreamDataService.createAppreciation.aprSlug = this.data.appreciation.aprSlug;
    this.activitySandboxService.addAppreciationCommentReplay(comment, this.index, idx);
    comment.replyTxt = '';
    this.replaylist = false;
  }
  /* add comment reply [end] */

  /* add Appreciation comment response[start] */
  commentResponse(comment, idx) {
    if (comment.yourCommentResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = comment.aprCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = null;
      comment.yourCommentResponse = 'like';
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.commentSlug = comment.aprCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = comment.yourCommentResponseSlug;
      comment.yourCommentResponse = '';
    }
    this.actStreamDataService.reply[idx] = false;
    this.activitySandboxService.appreciationCommentResponse(this.index);
  }
  /* add Appreciation comment response[end] */

  /* delete Appreciation comment[start] */
  deleteComment(idx) {
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected Comment?'
    this.actStreamDataService.deleteCommentPopUp[idx] = true;
  }
  conformDeleteComment(comment) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.aprCommentSlug;
    this.actStreamDataService.CommentsAndResponses.action = 'delete';
    this.actStreamDataService.createAppreciation.aprSlug = this.data.appreciation.aprSlug;
    this.activitySandboxService.deleteAppreciationComment(this.index);
    this.commentCount = this.commentCount - 1;
    this.actStreamDataService.resetPopUpBox();
    this.commentTxt = '';
  }
  /* delete Appreciation comment[start] */

  /* update Appreciation comment[start] */
  updateComment(comment) {
    this.commentTxt = comment.comment;
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.aprCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.CommentsAndResponses.action = 'update';
    this.actStreamDataService.createAppreciation.aprSlug = this.data.appreciation.aprSlug;
  }
  /* update Appreciation comment[start] */

  /* delete comment reply [Start] */
  deleteReply(reply, index) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = reply.aprCommentSlug;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected reply?'
    this.actStreamDataService.deleteReplyPopUp[index] = true;
  }
  conformDeleteReply(comment, idx) {
    this.actStreamDataService.CommentsAndResponses.action = 'delete';
    this.actStreamDataService.createAppreciation.aprSlug = this.data.appreciation.aprSlug;
    this.activitySandboxService.deleteAppreciationCommentReplay(comment, this.index, idx);
   
  }
  /* delete comment reply [end] */

  /* update comment reply[start] */
  updateCommentReply(reply, comment) {
    comment.replyTxt = reply.comment;
    this.actStreamDataService.CommentsAndResponses.commentSlug = reply.aprCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.replyTxt;
    this.actStreamDataService.CommentsAndResponses.action = 'update';
    this.actStreamDataService.createAppreciation.aprSlug = this.data.appreciation.aprSlug;
  }
  /* update comment reply[end] */

  /* add reply to comment reply[start] */
  replyToReply(reply, comment) {
    this.commentCreatorUserName = reply.commentCreatorUserName;
    comment.replyTxt = '@' + this.commentCreatorUserName + ' ';
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = reply.aprCommentSlug;
  }
  /* add reply to comment reply[end] */

  /* response reply[start] */
  replyResponse(reply, comment, idx) {
    if (reply.yourCommentResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = reply.aprCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = null;
      reply.yourCommentResponse = 'like';
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.commentSlug = reply.aprCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = reply.yourCommentResponseSlug;
      reply.yourCommentResponse = '';
    }
    this.activitySandboxService.getAppreciationCommentResponse(comment, this.index, idx);
  }
  /* response reply[end] */
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