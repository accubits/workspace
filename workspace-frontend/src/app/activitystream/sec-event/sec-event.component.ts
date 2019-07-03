import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../activity.sandbox';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-sec-event',
  templateUrl: './sec-event.component.html',
  styleUrls: ['./sec-event.component.scss']
})
export class SecEventComponent implements OnInit {

  @Input() data: any;
  @Input() index: number;
  responseCount: any;
  commentCount: any;
  loggedUser = '';
  commentTxt: any;
  replyTxt: any;
  commentCreatorUserName: '';
  idx: any;
  edited: boolean = false;
  hasGoing: boolean = false;
  hasDecline: boolean = false;
  goingCount: string;
  declineCount: string;
  startTime: any;
  endTime: any;
  startDate: any;
  endDate: any;
  singleDate: any;
  showCount: any;
  list = false;
  replaylist = false;
  listComment = false;
  public assetUrl = Configs.assetBaseUrl;

  constructor(public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService,
    private cookieService: CookieService) { }

  ngOnInit() {
    this.actStreamDataService.eventMessage.msg = '';
    this.commentCount = this.data.event.eventCommentsCount;
    this.responseCount = this.data.event.eventResponsesCount;
    this.loggedUser = this.cookieService.get('userSlug');
    this.actStreamDataService.comments[this.index] = false;
    this.actStreamDataService.resetPopUpBox();
    var startDateTime = new Date(this.data.event.eventStart * 1000);
    var endDateTime = new Date(this.data.event.eventEnd * 1000);
    this.startDate = startDateTime.toDateString();
    this.endDate = endDateTime.toDateString();
    this.startTime = startDateTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
    this.endTime = endDateTime.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
    this.goingCount = this.data.event.toUsers.filter(
      file => file.eventResponse === "going");
      this.declineCount = this.data.event.toUsers.filter(
        file => file.eventResponse === "decline");

    if (this.data.event.toUsers.length === 0) {
      this.edited = true;
      this.showCount = false;
     }
    else {
      let userSlug = this.cookieService.get('userSlug');
      let selFile = this.data.event.toUsers.filter(
        file => file.userSlug === userSlug)[0];
      if (selFile) {
        this.edited = true;
        if (selFile.eventResponse === "going") {
          this.showCount = true;
          this.hasGoing = true;
        }
        if (selFile.eventResponse === "decline") {
          this.showCount = true;
          this.hasDecline = true;
        }
      }
      else{
        this.edited = true;
        this.showCount = false;
      }
    }
  }

  /* show event moeoption [Start] */
  showEvent() {
    this.actStreamDataService.createEvent.index = this.index.toString();
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
    this.activitySandboxService.getEventDetails();
    this.actStreamDataService.eventShow.show = true;
    this.actStreamDataService.eventView.show = true;
  }
  /* show event moeoption [end] */

  /* close event moeoption [Start] */
  closeEvent() {
    this.actStreamDataService.eventShow.show = false;
    this.actStreamDataService.eventView.show = false;
  }
  /* close event moeoption [end] */

  /* event status update [Start] */
  eventStatusUpdate(eventSlug, eventResponse) {
    this.activitySandboxService.eventStatusUpdate(eventSlug, eventResponse);
  }
  /* event status update [end] */

  /* delete selected event [Start] */
  deleteEvent() {
    this.actStreamDataService.optionBtn[this.index] = false;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected Event?'
    this.actStreamDataService.deletePopUp[this.index] = true;
  }
  conformEventDelete() {
    this.actStreamDataService.createEvent.action = 'delete';
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
    this.activitySandboxService.createNewEvent();
  }
  cancelDelete() {
    this.actStreamDataService.resetPopUpBox();
  }
  /* delete selected event [end] */

  /* update event [Start] */
  updateEvent() {
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
    this.activitySandboxService.getEventDetails();
    this.actStreamDataService.createEvent.action = 'update';
    this.actStreamDataService.selectedWidget.selWctab = 'event';
    this.actStreamDataService.activityCreatePopUp.show = true;
  }
  /* update event [end] */

  /* get event response [Start] */
  eventResponse() {
    if (this.data.event.yourEventResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.responseSlug = null;
      this.data.event.yourEventResponse = 'like';
      this.responseCount = this.responseCount + 1;
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.responseSlug = this.data.event.yourEventResponseSlug;
      this.data.event.yourEventResponse = '';
      this.responseCount = this.responseCount - 1;
    }
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
    this.activitySandboxService.eventResponse(this.index);
  }
  /* get event response [end] */

  /* get event comments [Start] */
  getEventComments() {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
    this.activitySandboxService.getEventComments(this.index);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.comments[this.index] = !this.actStreamDataService.comments[this.index];
  }
  /* get event comments [ent] */

  /* add event comment[start] */
  addEventComment() {
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
    this.activitySandboxService.addEventComment(this.index);
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
  /* add event comment [end] */

  /* get comment Reply [start] */
  getCommentReply(comment, idx) {
    this.replyTxt = '';
    this.actStreamDataService.CommentsAndResponses.commentSlug = null;
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
    this.activitySandboxService.getEventCommentReply(comment, this.index, idx);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.reply[idx] = !this.actStreamDataService.reply[idx];
  }
  /* get comment Reply [end] */

  /* add event comment response[start] */
  commentResponse(comment) {
    if (comment.yourCommentResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = comment.eventCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = null;
      comment.yourCommentResponse = 'like';
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.commentSlug = comment.eventCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = comment.yourCommentResponseSlug;
      comment.yourCommentResponse = '';
    }
    this.activitySandboxService.eventCommentResponse(this.index);
  }
  /* add event comment response[end] */

  /* delete event comment[start] */
  deleteComment(idx) {
    this.actStreamDataService.cmtOptionBtn[idx] = false;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected Comment?';
    this.actStreamDataService.deleteCommentPopUp[idx] = true;
  }
  conformDeleteComment(comment) {
    this.actStreamDataService.resetPopUpBox();
    this.commentTxt = '';
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.eventCommentSlug;
    this.actStreamDataService.CommentsAndResponses.action = 'delete';
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
    this.activitySandboxService.deleteEventComment(this.index);
    this.commentCount = this.commentCount - 1;
  }
  /* delete event comment[end] */

  /* update event comment[start] */
  updateComment(comment) {
    this.commentTxt = comment.comment;
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.eventCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.CommentsAndResponses.action = 'update';
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
  }
  /* update event comment[start] */

  /* add comment reply [start] */
  addCommentReply(replyTxt, comment, idx) {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = comment.eventCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = replyTxt;
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
    this.activitySandboxService.addEventCommentReplay(comment, this.index, idx);
    comment.replyTxt = '';
    this.replaylist = false;
  }
  /* add comment reply [end] */

  /* add reply for comment reply [start] */
  replyToReply(reply, comment) {
    this.commentCreatorUserName = reply.commentCreatorUserName;
    comment.replyTxt = '@' + this.commentCreatorUserName + ' ';
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = reply.eventCommentSlug;
  }
  /* add reply for comment reply [end] */

  /* reply response[start] */
  replyResponse(reply, comment, idx) {
    if (reply.yourCommentResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = reply.eventCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = null;
      reply.yourCommentResponse = 'like';
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.commentSlug = reply.eventCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = reply.yourCommentResponseSlug;
      reply.yourCommentResponse = '';
    }
    this.activitySandboxService.getEventCommentResponse(comment, this.index, idx);
  }
  /* reply response[end] */

  /* delete comment reply [Start] */
  deleteReply(reply, index) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = reply.eventCommentSlug;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected reply?'
    this.actStreamDataService.deleteReplyPopUp[index] = true;
  }
  conformDeleteReply(comment, idx) {
    this.actStreamDataService.CommentsAndResponses.action = 'delete';
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
    this.activitySandboxService.deleteEventCommentReplay(comment, this.index, idx);
  }
  /* delete comment reply [end] */

  /* update comment reply[start] */
  updateCommentReply(reply, comment) {
    comment.replyTxt = reply.comment;
    this.actStreamDataService.CommentsAndResponses.commentSlug = reply.eventCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.replyTxt;
    this.actStreamDataService.CommentsAndResponses.action = 'update';
    this.actStreamDataService.createEvent.eventSlug = this.data.event.eventSlug;
  }
  /* update comment reply[end] */
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
