import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service'
import { ActivitySandboxService } from '../activity.sandbox';
import { CookieService } from 'ngx-cookie-service';
import { SettingsDataService } from '../../shared/services/settings-data.service';
import { ToastService } from '../../shared/services/toast.service';

@Component({
  selector: 'app-sec-poll',
  templateUrl: './sec-poll.component.html',
  styleUrls: ['./sec-poll.component.scss']
})
export class SecPollComponent implements OnInit {
  @Input() data: any;
  @Input() index: number;
  idx: any;
  responseCount: any;
  commentCount: any;
  loggedUser = '';
  commentTxt: any;
  replyTxt: any;
  commentCreatorUserName: string;
  creater: boolean = false;
  count: number = 0;
  vote: boolean = false;
  view: boolean = false;
  list = false;
  replaylist = false;
  listComment = false;
  public assetUrl = Configs.assetBaseUrl;

  constructor(public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService,
    private toastService: ToastService,
    public settingsDataService: SettingsDataService,
    private cookieService: CookieService) { }

  ngOnInit() {
    this.commentCount = this.data.poll.pollCommentsCount;
    this.responseCount = this.data.poll.pollResponsesCount;
    this.loggedUser = this.cookieService.get('userSlug');
    this.actStreamDataService.comments[this.index] = false;
    this.actStreamDataService.resetPopUpBox();
    
    for (let i = 0; i < this.data.poll.pollQuestions.length; i++) {
      let voted = this.data.poll.pollQuestions[i].answerOptions.filter(
        file => file.selected === true)[0];
        if(voted){
          this.data.poll.votted = true;
        }
        else
        {
          this.data.poll.votted = false;
        }
      var total = 0;
      for (let j = 0; j < this.data.poll.pollQuestions[i].answerOptions.length; j++) {
        total = total + this.data.poll.pollQuestions[i].answerOptions[j].pollResult.totalSelected;
       }
      for (let j = 0; j < this.data.poll.pollQuestions[i].answerOptions.length; j++) {
        this.data.poll.pollQuestions[i].answerOptions[j].pollResult.totalVotted = total;
      }
    }
    if (this.data.poll.pollCreatorSlug === this.cookieService.get('userSlug')) {
      this.creater = true;
    }
    for (let i = 0; i < this.data.poll.pollQuestions.length; i++) {
      let File = this.data.poll.pollQuestions[i].answerOptions.filter(
        file => file.selected === true)[0];
      if (File) {
        this.vote = true;
      }
    }
  }

  /* select option from multiple choice [Start] */
  multipleChoiceSelection(selected, pollQuestionId, pollOptId, Index) {
    if (selected) {
      let selQst = this.actStreamDataService.createPoll.pollQuestionsAnswers.filter(
        file => file.pollQuestionId === pollQuestionId)[0];
      if (selQst) {
        this.actStreamDataService.createPoll.pollQuestionsAnswers[Index].selectedAnswerOptions.push({ pollOptId: pollOptId });
      }
      else {
        this.actStreamDataService.createPoll.pollQuestionsAnswers.push({
          pollQuestionId: pollQuestionId,
          selectedAnswerOptions: [{ pollOptId: pollOptId }]
        });
      }
    }
    else {
      let selQst = this.actStreamDataService.createPoll.pollQuestionsAnswers.filter(
        file => file.pollQuestionId === pollQuestionId)[0];
      if (selQst) {
        let selAns = selQst.selectedAnswerOptions.filter(
          file => file.pollOptId === pollOptId)[0];
        let idx = selQst.selectedAnswerOptions.indexOf(selAns);
        selQst.selectedAnswerOptions.splice(idx, 1);
      }
    }
  }
  /*select option from multiple choice[end] */

  /* select option from single choice [Start] */
  singleChoiceSelection(qst, ans) {
    this.actStreamDataService.createPoll.pollQuestionsAnswers.push({
      pollQuestionId: qst.pollQuestionId,
      selectedAnswerOptions: [{ pollOptId: ans.pollOptId }]
    });
  }
  /* select option from single choice[end] */

  /* submit poll option [Start] */
  submitVote(pollSlug) {
    if (this.actStreamDataService.createPoll.pollQuestionsAnswers.length === 0) {
      this.toastService.Error('Please select any answer option');
      return
    }
    else {
      this.actStreamDataService.createPoll.pollSlug = pollSlug;
      this.activitySandboxService.submitVote();
      this.vote = true;
    }
  }
  /*  submit poll option  [end] */

  /* delete selected poll [Start] */
  closeVote(pollSlug) {
    this.actStreamDataService.createPoll.pollSlug = pollSlug;
    this.actStreamDataService.createPoll.status = 'Closed';
    this.activitySandboxService.closeVote();
  }
  /* delete selected poll [end] */

  /* update vote option [Start] */
  voteAgain() {
    this.vote = false;
    this.view = false;
    this.actStreamDataService.createPoll.pollQuestionsAnswers = [];
    for (let i = 0; i < this.data.poll.pollQuestions.length; i++) {
      let pollQuestionsAnswer = {
        pollQuestionId: this.data.poll.pollQuestions[i].pollQuestionId,
        selectedAnswerOptions: []
      };
      let selectedAnswerOptions = [];
      for (let j = 0; j < this.data.poll.pollQuestions[i].answerOptions.length; j++) {
        if (this.data.poll.pollQuestions[i].answerOptions[j].selected === true) {
          selectedAnswerOptions.push({
            pollOptId: this.data.poll.pollQuestions[i].answerOptions[j].pollOptId
          });
        }
      }
      pollQuestionsAnswer.selectedAnswerOptions = selectedAnswerOptions;
      this.actStreamDataService.createPoll.pollQuestionsAnswers.push(pollQuestionsAnswer);
    }
  }
  /* update vote option  [end] */

  /* view poll results [Start] */
  viewResults() {
    this.view = true;
  }
  /* view poll results [end] */

  /* delete selected poll [Start] */
  deletePoll() {
    this.actStreamDataService.optionBtn[this.index] = false;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected Poll?'
    this.actStreamDataService.deletePopUp[this.index] = true;
  }
  conformDeletePoll() {
    this.actStreamDataService.createPoll.action = 'delete';
    this.actStreamDataService.createPoll.pollSlug = this.data.poll.pollSlug;
    this.activitySandboxService.createNewPoll();
  }
  cancelDelete() {
    this.actStreamDataService.resetPopUpBox();
  }
  /* delete selected poll [end] */

  /* get poll response [Start] */
  pollResponse() {
    if (this.data.poll.yourPollResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.responseSlug = null;
      this.data.poll.yourPollResponse = 'like';
      this.responseCount = this.responseCount + 1;
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.responseSlug = this.data.poll.yourPollResponseSlug;
      this.data.poll.yourPollResponse = '';
      this.responseCount = this.responseCount - 1;
    }
    this.actStreamDataService.createPoll.pollSlug = this.data.poll.pollSlug;
    this.activitySandboxService.pollResponse(this.index);
  }
  /* get poll response [end] */

  /* get poll comments [Start] */
  getPollComments() {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
    this.actStreamDataService.createPoll.pollSlug = this.data.poll.pollSlug;
    this.activitySandboxService.getPollComments(this.index);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.comments[this.index] = !this.actStreamDataService.comments[this.index];
  }
  /* get poll comments [end] */

  /* delete poll comment[start] */
  deleteComment(idx) {
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected Comment?';
    this.actStreamDataService.deleteCommentPopUp[idx] = true;
  }
  conformDeleteComment(comment) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.pollCommentSlug;
    this.actStreamDataService.CommentsAndResponses.action = 'delete';
    this.actStreamDataService.createPoll.pollSlug = this.data.poll.pollSlug;
    this.activitySandboxService.deletePollComment(this.index);
    this.commentCount = this.commentCount - 1;
    this.actStreamDataService.resetPopUpBox();
    this.commentTxt = '';
  }
  /* delete poll comment[end] */

  /* update poll comment[start] */
  updateComment(comment) {
    this.commentTxt = comment.comment;
    this.actStreamDataService.CommentsAndResponses.commentSlug = comment.pollCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.CommentsAndResponses.action = 'update';
    this.actStreamDataService.createPoll.pollSlug = this.data.poll.pollSlug;
  }
  /* update poll comment[end] */

  /* add poll comment response[start] */
  getResponseForComment(comment, idx) {
    if (comment.yourCommentResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = comment.pollCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = null;
      comment.yourCommentResponse = 'like';
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.commentSlug = comment.pollCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = comment.yourCommentResponseSlug;
      comment.yourCommentResponse = '';
    }
    this.actStreamDataService.reply[idx] = false;
    this.activitySandboxService.pollCommentResponse(this.index);
  }
  /* add poll comment response[end] */

  /* get comment Reply [start] */
  getCommentReply(comment, idx) {
    this.replyTxt = '';
    this.actStreamDataService.CommentsAndResponses.commentSlug = null;
    this.actStreamDataService.createPoll.pollSlug = this.data.poll.pollSlug;
    this.activitySandboxService.getPollCommentReply(comment, this.index, idx);
    this.actStreamDataService.CommentsAndResponses.action = 'create';
    this.actStreamDataService.reply[idx] = !this.actStreamDataService.reply[idx];
  }
  /* get comment Reply [end] */

  /* delete comment reply [Start] */
  deleteReply(reply, index) {
    this.actStreamDataService.CommentsAndResponses.commentSlug = reply.pollCommentSlug;
    this.actStreamDataService.deleteMessage.msg = 'Are you sure you want to delete selected reply?'
    this.actStreamDataService.deleteReplyPopUp[index] = true;
  }
  conformDeleteReply(comment, idx) {
    this.actStreamDataService.CommentsAndResponses.action = 'delete';
    this.actStreamDataService.createPoll.pollSlug = this.data.poll.pollSlug;
    this.activitySandboxService.deletePollCommentReplay(comment, this.index, idx);

  }
  /* delete comment reply [end] */

  /* update comment reply[start] */
  updateCommentReply(reply, comment) {
    comment.replyTxt = reply.comment;
    this.actStreamDataService.CommentsAndResponses.commentSlug = reply.pollCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.replyTxt;
    this.actStreamDataService.CommentsAndResponses.action = 'update';
    this.actStreamDataService.createPoll.pollSlug = this.data.poll.pollSlug;
  }
  /* update comment reply[end] */

  /* add reply to comment reply[start] */
  replyToReply(reply, comment) {
    this.commentCreatorUserName = reply.commentCreatorUserName;
    comment.replyTxt = '@' + this.commentCreatorUserName + ' ';
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = reply.pollCommentSlug;
  }
  /*  add reply to comment reply[end] */

  /* poll comment reply response[start] */
  replyResponse(reply, comment, idx) {
    if (reply.yourCommentResponse !== 'like') {
      this.actStreamDataService.CommentsAndResponses.action = 'create';
      this.actStreamDataService.CommentsAndResponses.commentSlug = reply.pollCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = null;
      reply.yourCommentResponse = 'like';
    }
    else {
      this.actStreamDataService.CommentsAndResponses.action = 'delete';
      this.actStreamDataService.CommentsAndResponses.commentSlug = reply.pollCommentSlug;
      this.actStreamDataService.CommentsAndResponses.commentResponseSlug = reply.yourCommentResponseSlug;
      reply.yourCommentResponse = '';
    }
    this.activitySandboxService.getPollCommentResponse(comment, this.index, idx);
  }
  /* poll comment reply response[end] */

  /* add poll comment[start] */
  addPollComment() {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = null;
    this.actStreamDataService.CommentsAndResponses.commentTxt = this.commentTxt;
    this.actStreamDataService.createPoll.pollSlug = this.data.poll.pollSlug;
    this.activitySandboxService.addPollComment(this.index);
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
  /* add poll comment [end] */

  /* add comment reply [start] */
  addCommentReply(replyTxt, comment, idx) {
    this.actStreamDataService.CommentsAndResponses.parentCommentSlug = comment.pollCommentSlug;
    this.actStreamDataService.CommentsAndResponses.commentTxt = replyTxt;
    this.actStreamDataService.createPoll.pollSlug = this.data.poll.pollSlug;
    this.activitySandboxService.addPollCommentReplay(comment, this.index, idx);
    comment.replyTxt = '';
    this.replaylist = false;
  }
  /* add comment reply [start] */

  /* update poll [Start] */
  updatePoll() {
    this.actStreamDataService.createPoll.action = 'update';
    this.actStreamDataService.selectedWidget.selWctab = 'poll';
    this.actStreamDataService.activityCreatePopUp.show = true;
    this.actStreamDataService.createPoll.pollQuestions = this.data.poll.pollQuestions;
    for (let i = 0; i < this.actStreamDataService.createPoll.pollQuestions.length; i++) {
      this.actStreamDataService.createPoll.pollQuestions[i].action = 'update';
    }
    this.actStreamDataService.toUsers.toUsers = [];
    for (let i = 0; i < this.data.poll.toUsers.length; i++) {
      this.actStreamDataService.toUsers.toUsers.push({
        userSlug: this.data.poll.toUsers[i].userSlug,
        name: this.data.poll.toUsers[i].userName
      });
    }
    this.actStreamDataService.createPoll.pollSlug = this.data.poll.pollSlug;
    this.actStreamDataService.createPoll.pollTitle = this.data.poll.pollTitle;
    this.actStreamDataService.msgEditor.text = this.data.poll.pollDesc;
    this.actStreamDataService.toUsers.toAllEmployee = this.data.poll.toAllEmployee;
  }
  /* update poll [end] */
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