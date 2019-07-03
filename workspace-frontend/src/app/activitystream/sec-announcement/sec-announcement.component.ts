import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service'
import { ActivitySandboxService } from '../activity.sandbox';
import { CookieService } from 'ngx-cookie-service';


@Component({
  selector: 'app-sec-announcement',
  templateUrl: './sec-announcement.component.html',
  styleUrls: ['./sec-announcement.component.scss']
})
export class SecAnnouncementComponent implements OnInit {
  @Input() data: any;
  @Input() index: number;
  edited: boolean = false;
  hasRead: boolean = false;
  count: string;
  responseCount: any;
  commentCount: any;
  loggedUser = '';
  commentTxt: any;
  replyTxt: any;
  commentCreatorUserName: '';
  list = false;
  replaylist = false;
  listComment = false;
  viewMore = false;
  viewLess = false;
  trimmedString: string;
  public assetUrl = Configs.assetBaseUrl;

  constructor(public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService,
    private cookieService: CookieService) { }

  ngOnInit() {
    let x = this.data.announcement.ancDesc.split("</p>");
    if (x.length > 4) {
      this.viewMore = true;
      this.viewLess = false;
      this.trimmedString = x[0] + x[1] + x[2] + x[length - 1];
      this.trimmedString = this.trimmedString.replace('undefined', '');
    }
    this.activitySandboxService.getReposiblePerson();
    this.commentCount = this.data.announcement.announcementCommentsCount;
    this.responseCount = this.data.announcement.announcementResponsesCount;
    this.loggedUser = this.cookieService.get('userSlug');
    this.actStreamDataService.comments.show = false;
    this.actStreamDataService.resetPopUpBox();
    this.count = this.data.announcement.toUsers.filter(
      file => file.hasRead === true);
    if (this.data.announcement.toAllEmployee) {
      this.edited = true;
      let userSlug = this.cookieService.get('userSlug');
      let selFile = this.data.announcement.toUsers.filter(
        file => file.userSlug === userSlug)[0];
      if (selFile) {
        this.edited = true;
        if (selFile.hasRead) {
          this.hasRead = true;
        }
      }
    }
    else {
      let userSlug = this.cookieService.get('userSlug');
      let selFile = this.data.announcement.toUsers.filter(
        file => file.userSlug === userSlug)[0];
      if (selFile) {
        this.edited = true;
        if (selFile.hasRead) {
          this.hasRead = true;
        }
      }
    }
  }

  showMore() {
    this.viewMore = false;
    this.viewLess = true;
  }

  showLess() {
    this.viewMore = true;
    this.viewLess = false;
  }

  /* Update announcement read status[Start] */
  makeHasRead(ancSlug) {
    this.activitySandboxService.makeHasRead(ancSlug);
  }
  /* Update announcement read status[end] */

  /* delete announcement [Start] */
  deleteAnnouncement() {
    this.actStreamDataService.optionBtn[this.index] = false;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected Announcement?'
    this.actStreamDataService.deletePopUp[this.index] = true;
  }
  conformDelete() {
    this.actStreamDataService.createAnnouncement.action = 'delete';
    this.actStreamDataService.createAnnouncement.ancSlug = this.data.announcement.ancSlug;
    this.activitySandboxService.createNewAnnouncement();
  }
  cancelDelete() {
    this.actStreamDataService.resetPopUpBox();
  }
  /* delete announcement [end] */

  /* edit announcement[Start] */
  updateAnnouncement() {
    this.actStreamDataService.createAnnouncement.action = 'update';
    this.actStreamDataService.selectedWidget.selWctab = 'announcement';
    this.actStreamDataService.activityCreatePopUp.show = true;
    this.actStreamDataService.toUsers.toUsers = [];
    for (let i = 0; i < this.data.announcement.toUsers.length; i++) {
      this.actStreamDataService.toUsers.toUsers.push({
        userSlug: this.data.announcement.toUsers[i].userSlug,
        name: this.data.announcement.toUsers[i].userName
      });
    }
    this.actStreamDataService.createAnnouncement.ancSlug = this.data.announcement.ancSlug;
    this.actStreamDataService.createAnnouncement.ancTitle = this.data.announcement.ancTitle;
    this.actStreamDataService.createAnnouncement.ancDesc = this.data.announcement.ancDesc;
    this.actStreamDataService.msgEditor.text = this.data.announcement.ancDesc;
    this.actStreamDataService.toUsers.toAllEmployee = this.data.announcement.toAllEmployee;
  }
  /* edit announcement[end] */

  /* announcement response[Start] */
  announcementResponse() {
    if (this.data.announcement.yourAnnouncementResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.responseSlug = null;
      this.data.announcement.yourAnnouncementResponse = 'like';
      this.responseCount = this.responseCount + 1;
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.data.announcement.yourAnnouncementResponse = '';
      this.actStreamDataService.CommentsAndResponses.responseSlug = this.data.announcement.yourAnnouncementResponseSlug;
      this.responseCount = this.responseCount - 1;
    }
    this.actStreamDataService.createAnnouncement.ancSlug = this.data.announcement.ancSlug;
    this.activitySandboxService.announcementResponse(this.index);
  }
  /* announcement response[end] */

  /* get announcement comment[Start] */
  getAnnouncementComments() {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
    this.actStreamDataService.createAnnouncement.ancSlug = this.data.announcement.ancSlug;
    this.activitySandboxService.getAnnouncementComments(this.index);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.comments[this.index] = !this.actStreamDataService.comments[this.index];
  }
  /* get announcement comment[end] */

  /* get comment Reply [start] */
  getCommentReply(comment, idx) {
    this.replyTxt = '';
    this.actStreamDataService.CommentsAndResponses.commentSlug = null;
    this.actStreamDataService.createAnnouncement.ancSlug = this.data.announcement.ancSlug;
    this.activitySandboxService.getAnnouncemetnCommentReply(comment, this.index, idx);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.reply[idx] = !this.actStreamDataService.reply[idx];
  }
  /* get comment Reply [end] */

  /* add announcement comment[Start] */
  addAnnouncemetComment() {
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.createAnnouncement.ancSlug = this.data.announcement.ancSlug;
    this.activitySandboxService.addAnnouncementComment(this.index);
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
  /* add announcement commwent[end] */

  /* add comment reply [start] */
  addCommentReply(replyTxt, comment, idx) {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = comment.ancCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = replyTxt;
    this.actStreamDataService.createAnnouncement.ancSlug = this.data.announcement.ancSlug;
    this.activitySandboxService.addAnnouncemetCommentReplay(comment, this.index, idx);
    comment.replyTxt = '';
    this.replaylist = false;
  }
  /* add comment reply [end] */

  /* response for comment[Start] */
  getResponseForComment(comment, idx) {
    if (comment.yourCommentResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = comment.ancCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = null;
      comment.yourCommentResponse = 'like';
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.commentSlug = comment.ancCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = comment.yourCommentResponseSlug;
      comment.yourCommentResponse = '';
    }
    this.actStreamDataService.reply[idx] = false;
    this.activitySandboxService.announcementCommentResponse(this.index);
  }
  /* response for comment[end] */

  /* delete announcement comment[Start] */
  deleteComment(idx) {
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected Comment?'
    this.actStreamDataService.deleteCommentPopUp[idx] = true;
  }
  conformDeleteComment(comment) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.ancCommentSlug;
    this.actStreamDataService.CommentsAndResponses.action = 'delete';
    this.actStreamDataService.createAnnouncement.ancSlug = this.data.announcement.ancSlug;
    this.activitySandboxService.deleteAnnouncementComment(this.index);
    this.commentCount = this.commentCount - 1;
    this.actStreamDataService.resetPopUpBox();
    this.commentTxt = '';
  }
  /* delete announcement comment[end] */

  /* Update announcement comment[Start] */
  updateComment(comment) {
    this.commentTxt = comment.comment;
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.ancCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.CommentsAndResponses.action = 'update';
    this.actStreamDataService.createAnnouncement.ancSlug = this.data.announcement.ancSlug;
  }
  /* Update announcement comment[end] */

  /* delete comment reply [Start] */
  deleteReply(reply, index) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = reply.ancCommentSlug;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected reply?'
    this.actStreamDataService.deleteReplyPopUp[index] = true;
  }
  conformDeleteReply(comment, idx) {
    this.actStreamDataService.CommentsAndResponses.action = 'delete';
    this.actStreamDataService.createAnnouncement.ancSlug = this.data.announcement.ancSlug;
    this.activitySandboxService.deleteAnnouncementCommentReplay(comment, this.index, idx);
  }
  /* delete comment reply [end] */

  /* update comment reply[start] */
  updateCommentReply(reply, comment) {
    comment.replyTxt = reply.comment;
    this.actStreamDataService.CommentsAndResponses.commentSlug = reply.ancCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.replyTxt;
    this.actStreamDataService.CommentsAndResponses.action = 'update';
    this.actStreamDataService.createAnnouncement.ancSlug = this.data.announcement.ancSlug;
  }
  /* update comment reply[end] */

  /* add reply to comment reply[start] */
  replyToReply(reply, comment) {
    this.commentCreatorUserName = reply.commentCreatorUserName;
    comment.replyTxt = '@' + this.commentCreatorUserName + ' ';
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = reply.commentSlug;
  }
  /* add reply to comment reply[end] */

  /* reply response[start] */
  replyResponse(reply, comment, idx) {
    if (reply.yourCommentResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = reply.ancCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = null;
      reply.yourCommentResponse = 'like';
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.commentSlug = reply.ancCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = reply.yourCommentResponseSlug;
      reply.yourCommentResponse = '';
    }
    this.activitySandboxService.getAnnouncementCommentResponse(comment, this.index, idx);
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
