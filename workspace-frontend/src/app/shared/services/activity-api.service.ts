import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Observable } from "rxjs/Observable";
import { CookieService } from 'ngx-cookie-service';
import { Routes, RouterModule, Router } from '@angular/router';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';
import 'rxjs/add/observable/throw';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { TaskPostDataService } from './task-post-data.service';
import { UtilityService } from './utility.service'

@Injectable()
export class ActivityApiService {
  constructor(
    private cookieService: CookieService,
    private utilityService: UtilityService,
    private actStreamDataService: ActStreamDataService,
    private taskPostDataService: TaskPostDataService,
    private http: HttpClient
  ) { }

  /* Fetch ActivityStream [Start]*/
  fetchActivityStream(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/fetchActivityStream'
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "tab": this.actStreamDataService.fetchActivityStream.selectedTab,
      "page": this.actStreamDataService.fetchActivityStream.page,
      "perPage": this.actStreamDataService.fetchActivityStream.perPage,
    }
    //let data = this.cookieService.get('orgSlug');
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Fetch ActivityStream [End] */

  /* Get Rsponsible Person[Start] */
  getResponsiblePersons(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/orguserlist'
    let data = {
      'org_slug': this.cookieService.get('orgSlug'),
      'q': this.actStreamDataService.responsiblePersons.searchParticipantsTxt,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Get Rsponsible Person[End] */

  /* Get Reward Participants[Start] */
  getRewardparticipants(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/orguserlist'
    let data = {
      'org_slug': this.cookieService.get('orgSlug'),
      'q': this.actStreamDataService.rewardPersons.searchRewardParticipantsTxt,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Get Reward Participants[End] */

 
   /************************************** message Api's start ******************************/
  /* Creating new message[Start] */
  createNewMessage(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/message';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'action': this.actStreamDataService.createMessage.action,
      'msgSlug': this.actStreamDataService.createMessage.msgSlug,
      'msgTitle': this.actStreamDataService.createMessage.msgTitle,
      'msgDesc': this.actStreamDataService.createMessage.msgDesc,
      'toAllEmployee': this.actStreamDataService.toUsers.toAllEmployee,
      'toUsers': this.actStreamDataService.toUsers.toUsers
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Creating new message[End] */

  /* get Message Comments And Responses[Start] */
  getMessageCommentsAndResponses(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/getMessageCommentsAndResponses'
    let data = {
      "msgSlug": this.actStreamDataService.createMessage.msgSlug,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* get Message Comments And Responses[End] */

  /* message response[Start] */
  messageResponse(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/messageResponse';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'msgSlug': this.actStreamDataService.createMessage.msgSlug,
      'messageResponseSlug': this.actStreamDataService.CommentsAndResponses.responseSlug,
      'action': this.actStreamDataService.CommentsAndResponses.action,
      'response': 'like'
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* message response[End] */

  /* add message comment [Start] */
  messageComment(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/messageComment';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'commentSlug': this.actStreamDataService.CommentsAndResponses.commentSlug,
      'parentCommentSlug': this.actStreamDataService.CommentsAndResponses.parentCommentSlug,
      'comment': this.actStreamDataService.CommentsAndResponses.commentTxt,
      'action': this.actStreamDataService.CommentsAndResponses.action,
      'msgSlug': this.actStreamDataService.createMessage.msgSlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* add message comment[End] */

  /* message comment response[Start] */
  messageCommentResponse(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/messageCommentResponse';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'commentSlug': this.actStreamDataService.CommentsAndResponses.commentSlug,
      'commentResponseSlug': this.actStreamDataService.CommentsAndResponses.commentResponseSlug,
      'response': 'like',
      'action': this.actStreamDataService.CommentsAndResponses.action
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* message comment response[End] */
 /************************************** message Api's end ******************************/


 /************************************** task Api's start ******************************/
  /* Creating new task[Start] */
  createNewTask(): Observable<any> {
   // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task';
    var fd = new FormData();
    fd.append('data', this.taskPostDataService.prepareActivityTaskPost());
    for (let i = 0; i < this.actStreamDataService.createTask.fileList.length; i++) {
      fd.append('file[]', this.actStreamDataService.createTask.fileList[i]);
    }
    // Preparing HTTP Call
    return this.http.post(URL, fd)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Creating new task[End] */

   /* Get Task/birthday widget details[Start] */
   getTaskWidgetDetails(): Observable<any> {
    let URL = Configs.api + 'social/taskWidgetActivityStream'
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'taskDateRange': this.actStreamDataService.taskWidgetDetails.taskDateRange,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Get Task/birthday widget details[End] */
  

  /* update task[Start] */
  updateTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task/' + this.actStreamDataService.createTask.taskSlug;
    var fd = new FormData();
    fd.append('data', this.taskPostDataService.prepareActivityTaskPost());
    for (let i = 0; i < this.actStreamDataService.createTask.fileList.length; i++) {
      fd.append('file[]', this.actStreamDataService.createTask.fileList[i]);
    }
    // Preparing HTTP Call
    return this.http.post(URL, fd)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* update task[End] */

  /* get task details[Start] */
  fechTaskDetails(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task/' + this.actStreamDataService.createTask.taskSlug + '/edit';
    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* get task details[End] */

  /* delete task[Start] */
  deleteTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task/' + this.actStreamDataService.createTask.taskSlug;
    // Preparing HTTP Call
    return this.http.delete(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* delete task[End] */

  /* get Task Comments And Responses[Start] */
  getTaskCommentsAndResponses(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/task/' + this.actStreamDataService.createTask.taskSlug + '/comments';
    // Preparing HTTP Call
    return this.http.get(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* get Task Comments And Responses[End] */

  /* add task comment[Start] */
  addTaskComment(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/comment';
    let data = {
      'description': this.actStreamDataService.CommentsAndResponses.commentTxt,
      'reply_comment_slug': this.actStreamDataService.CommentsAndResponses.parentCommentSlug,
      'task_slug': this.actStreamDataService.createTask.taskSlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* add task comment[End] */

  /* task comment response[Start] */
  taskCommentResponse(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/comment/' + this.actStreamDataService.CommentsAndResponses.commentSlug + '/like';
    let data = {
      "like": this.actStreamDataService.CommentsAndResponses.taskCommentLike
    }
    // Preparing HTTP Call
    return this.http.post(URL, data)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* task comment response[End] */

  /* delete task comment[Start] */
  deleteTaskComment(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/taskCommentDelete';
    let data = {
      'commentSlug': this.actStreamDataService.CommentsAndResponses.commentSlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* delete task comment[End] */
  /************************************** task Api's end ******************************/


  /************************************** event Api's start ******************************/
  /* Creating new Event[Start] */
  createNewEvent(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/event'
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'action': this.actStreamDataService.createEvent.action,
      'eventSlug': this.actStreamDataService.createEvent.eventSlug,
      'eventTitle': this.actStreamDataService.createEvent.eventTitle,
      'eventDesc': this.actStreamDataService.createEvent.eventDesc,
      'eventStart': this.actStreamDataService.createEvent.eventStart,
      'eventEnd': this.actStreamDataService.createEvent.eventEnd,
      'eventAllDay': this.actStreamDataService.createEvent.eventAllDay,
      'toAllEmployee': this.actStreamDataService.toUsers.toAllEmployee,
      'toUsers': this.actStreamDataService.toUsers.toUsers,
      'reminder': this.actStreamDataService.createEvent.reminder,
      'eventLocation': this.actStreamDataService.createEvent.eventLocation,
      'eventRepeat': this.actStreamDataService.createEvent.eventRepeat,
      'eventImportance': this.actStreamDataService.createEvent.eventImportance,
      'eventAvailability': this.actStreamDataService.createEvent.eventAvailability
    };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Creating new Event[End] */

  /* change event status[Start] */
  eventStatusUpdate(eventSlug, eventResponse): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/setEventStatus'
    let data = {
      eventSlug,
      eventResponse
    };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* change event status[End] */

  /* get Event details[Start] */
  getEventDetails(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/getEvent';
    let data = {
      'eventSlug': this.actStreamDataService.createEvent.eventSlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* get Event details[end] */

  /* get event Comments And Responses[Start] */
  getEventCommentsAndResponses(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/getEventCommentsAndResponses'
    let data = {
      "eventSlug": this.actStreamDataService.createEvent.eventSlug,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* get event Comments And Responses[End] */

  /* event response[Start] */
  eventResponse(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/eventResponse';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'eventSlug': this.actStreamDataService.createEvent.eventSlug,
      'eventResponseSlug': this.actStreamDataService.CommentsAndResponses.responseSlug,
      'action': this.actStreamDataService.CommentsAndResponses.action,
      'response': 'like'
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* event response[End] */

  /* add event comment[Start] */
  eventComment(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/eventComment';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'commentSlug': this.actStreamDataService.CommentsAndResponses.commentSlug,
      'parentCommentSlug': this.actStreamDataService.CommentsAndResponses.parentCommentSlug,
      'comment': this.actStreamDataService.CommentsAndResponses.commentTxt,
      'action': this.actStreamDataService.CommentsAndResponses.action,
      'eventSlug': this.actStreamDataService.createEvent.eventSlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* add event comment[End] */

  /* event comment response[Start] */
  eventCommentResponse(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/eventCommentResponse';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'commentSlug': this.actStreamDataService.CommentsAndResponses.commentSlug,
      'commentResponseSlug': this.actStreamDataService.CommentsAndResponses.commentResponseSlug,
      'response': 'like',
      'action': this.actStreamDataService.CommentsAndResponses.action
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* event comment response[End] */
  /************************************** event Api's end ******************************/

 /************************************** announcemet Api's start ******************************/
  /* Creating new Announcement[Start] */
  createNewAnnouncement(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/announcement'
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'action': this.actStreamDataService.createAnnouncement.action,
      'ancSlug': this.actStreamDataService.createAnnouncement.ancSlug,
      'ancTitle': this.actStreamDataService.createAnnouncement.ancTitle,
      'ancDesc': this.actStreamDataService.createAnnouncement.ancDesc,
      'hasRead': this.actStreamDataService.createAnnouncement.hasRead,
      'toAllEmployee': this.actStreamDataService.toUsers.toAllEmployee,
      'toUsers': this.actStreamDataService.toUsers.toUsers,
    };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Creating new Announcement[End] */

  /* change announcement read status[Start] */
  makeHasRead(ancSlug): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/setAnnouncementReadStatus'
    let data = { ancSlug };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* change announcement read status[End] */

  /* get announcement Comments And Responses[Start] */
  getAnnouncementCommentsAndResponses(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/getAnnouncementCommentsAndResponses'
    let data = {
      "ancSlug": this.actStreamDataService.createAnnouncement.ancSlug,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* get announcement Comments And Responses[End] */

  /* announcemet response[Start] */
  announcementResponse(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/announcementResponse';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'ancSlug': this.actStreamDataService.createAnnouncement.ancSlug,
      'ancResponseSlug': this.actStreamDataService.CommentsAndResponses.responseSlug,
      'action': this.actStreamDataService.CommentsAndResponses.action,
      'response': 'like'
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* announcemet response[End] */

  /* add announcement comment[Start] */
  announcementComment(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/announcementComment';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'commentSlug': this.actStreamDataService.CommentsAndResponses.commentSlug,
      'parentCommentSlug': this.actStreamDataService.CommentsAndResponses.parentCommentSlug,
      'comment': this.actStreamDataService.CommentsAndResponses.commentTxt,
      'action': this.actStreamDataService.CommentsAndResponses.action,
      'ancSlug': this.actStreamDataService.createAnnouncement.ancSlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* add announcement comment[End] */

  /* announcemet comment response[Start] */
  announcementCommentResponse(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/announcementCommentResponse';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'commentSlug': this.actStreamDataService.CommentsAndResponses.commentSlug,
      'commentResponseSlug': this.actStreamDataService.CommentsAndResponses.commentResponseSlug,
      'response': 'like',
      'action': this.actStreamDataService.CommentsAndResponses.action
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* announcemet comment response[End] */
 /************************************** announcemet Api's end ******************************/

  /************************************** poll Api's start ******************************/
  /* Creating new poll[Start] */
  createNewPoll(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/poll'
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'action': this.actStreamDataService.createPoll.action,
      'pollSlug': this.actStreamDataService.createPoll.pollSlug,
      'pollTitle': this.actStreamDataService.createPoll.pollTitle,
      'pollDesc': this.actStreamDataService.createPoll.pollDesc,
      'status': this.actStreamDataService.createPoll.status,
      'pollQuestions': this.actStreamDataService.createPoll.pollQuestions,
      'toAllEmployee': this.actStreamDataService.toUsers.toAllEmployee,
      'toUsers': this.actStreamDataService.toUsers.toUsers,
    };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Creating new poll[End] */

  /* submit poll[Start] */
  submitVote(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/setPollAnswers'
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "pollSlug": this.actStreamDataService.createPoll.pollSlug,
      "pollQuestionsAnswers": this.actStreamDataService.createPoll.pollQuestionsAnswers
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* submit poll[End] */

  /* close Vote[Start] */
  closeVote(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/setPollStatus'
    let data = {
      "pollSlug": this.actStreamDataService.createPoll.pollSlug,
      "status": this.actStreamDataService.createPoll.status
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* close Vote[End] */

  /* get poll Comments And Responses[Start] */
  getPollCommentsAndResponses(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/getPollCommentsAndResponses'
    let data = {
      "pollSlug": this.actStreamDataService.createPoll.pollSlug,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* get poll Comments And Responses[End] */

  /* poll response[Start] */
  pollResponse(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/pollResponse';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'pollSlug': this.actStreamDataService.createPoll.pollSlug,
      'pollResponseSlug': this.actStreamDataService.CommentsAndResponses.responseSlug,
      'action': this.actStreamDataService.CommentsAndResponses.action,
      'response': 'like'
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* poll response[End] */

  /* add poll comment[Start] */
  pollComment(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/pollComment';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'commentSlug': this.actStreamDataService.CommentsAndResponses.commentSlug,
      'parentCommentSlug': this.actStreamDataService.CommentsAndResponses.parentCommentSlug,
      'comment': this.actStreamDataService.CommentsAndResponses.commentTxt,
      'action': this.actStreamDataService.CommentsAndResponses.action,
      'pollSlug': this.actStreamDataService.createPoll.pollSlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* add poll comment[End] */

  /* poll comment response[Start] */
  pollCommentResponse(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/pollCommentResponse';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'commentSlug': this.actStreamDataService.CommentsAndResponses.commentSlug,
      'commentResponseSlug': this.actStreamDataService.CommentsAndResponses.commentResponseSlug,
      'response': 'like',
      'action': this.actStreamDataService.CommentsAndResponses.action
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* poll comment response[End] */
  /************************************** poll Api's end ******************************/

  /************************************** Appreciation Api's start ******************************/
  /* Creating new Appreciation[Start] */
  createNewAppreciation(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/appreciation'
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'action': this.actStreamDataService.createAppreciation.action,
      'aprSlug': this.actStreamDataService.createAppreciation.aprSlug,
      'aprTitle': this.actStreamDataService.createAppreciation.aprTitle,
      'aprDesc': this.actStreamDataService.createAppreciation.aprDesc,
      'status': this.actStreamDataService.createAppreciation.status,
      'recipients': this.actStreamDataService.createAppreciation.recipients,
      'aprHasDisplayDuration': this.actStreamDataService.createAppreciation.aprHasDisplayDuration,
      'aprDisplayStart': this.actStreamDataService.createAppreciation.aprDisplayStart,
      'aprDisplayEnd': this.actStreamDataService.createAppreciation.aprDisplayEnd,
      'toAllEmployee': this.actStreamDataService.toUsers.toAllEmployee,
      'toUsers': this.actStreamDataService.toUsers.toUsers,
    };
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Creating new Appreciation[End] */

  /* get Appreciation Comments And Responses[Start] */
  getAppreciationCommentsAndResponses(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/getAppreciationCommentsAndResponses'
    let data = {
      "aprSlug": this.actStreamDataService.createAppreciation.aprSlug,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* get Appreciation Comments And Responses[End] */

  /* appreciation response[Start] */
  appreciationResponse(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/appreciationResponse';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'aprSlug': this.actStreamDataService.createAppreciation.aprSlug,
      'aprResponseSlug': this.actStreamDataService.CommentsAndResponses.responseSlug,
      'action': this.actStreamDataService.CommentsAndResponses.action,
      'response': 'like'
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* appreciation response[End] */

  /* add Appreciation Comment[Start] */
  appreciationComment(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/appreciationComment';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'commentSlug': this.actStreamDataService.CommentsAndResponses.commentSlug,
      'parentCommentSlug': this.actStreamDataService.CommentsAndResponses.parentCommentSlug,
      'comment': this.actStreamDataService.CommentsAndResponses.commentTxt,
      'action': this.actStreamDataService.CommentsAndResponses.action,
      'aprSlug': this.actStreamDataService.createAppreciation.aprSlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* add Appreciation Comment[end] */


  /* Appreciation comment response[Start] */
  appreciationCommentResponse(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'social/appreciationCommentResponse';
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'commentSlug': this.actStreamDataService.CommentsAndResponses.commentSlug,
      'commentResponseSlug': this.actStreamDataService.CommentsAndResponses.commentResponseSlug,
      'response': 'like',
      'action': this.actStreamDataService.CommentsAndResponses.action
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Appreciation comment response[End] */
  /************************************** Appreciation Api's end ******************************/

  /* Manage Task Status[Start] */
  manageTaskStatus(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/update-task-status'
    let data = this.taskPostDataService.widgetStatusPost();
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Manage Task Status[End] */

   /* Manage Task Status[Start] */
   completeTask(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'taskmanagement/update-task-status'
    let data = {
      "tasks": this.actStreamDataService.taskRunManagement.selectedTaskIds,
      "action": 'single',
      "status": this.actStreamDataService.taskRunManagement.status
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* Manage Task Status[End] */

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
