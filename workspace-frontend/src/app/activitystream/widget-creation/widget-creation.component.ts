import { Component, OnInit } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../activity.sandbox';
import { TaskDataService } from '../../shared/services/task-data.service';
import { UtilityService } from '../../shared/services/utility.service';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-widget-creation',
  templateUrl: './widget-creation.component.html',
  styleUrls: ['./widget-creation.component.scss']
})
export class WidgetCreationComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  activeRpTab = 'all';
  userlistingdrop = false;
  rewardParticipantslistingdrop = false;
  reminderDisabled = true;
  isValidated = true;
  typeDd = false;
  AvaDd = false;
  repDd = false;
  impDd = false;
  startTime: any;
  endTime: any;
  /* Preparing Owl Date[Start] */
  date: Date = new Date();
  settings = {
    bigBanner: true,
    timePicker: true,
    format: 'dd-MM-yyyy',
    defaultOpen: false,
    hour12Timer: true
  };
  /* Preparing Owl Date[End] */

  constructor(
    public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService,
    public taskDataService: TaskDataService,
    private utilityService: UtilityService,
    public spinner: Ng4LoadingSpinnerService,
    private cookieService: CookieService) { }

  ngOnInit() {
    this.actStreamDataService.activityCreatePopUp.show = false;
    this.actStreamDataService.showCreatetaskpopup.show = false;
    this.actStreamDataService.createTask.responsiblePerson = {
      responsiblePersonId: this.cookieService.get('userSlug'),
      responsiblePersonName: this.cookieService.get('userName')
    }
    this.actStreamDataService.createTask.approver = {
      approverName: this.cookieService.get('userName'),
      approverSlug: this.cookieService.get('userSlug')
    }
    this.activitySandboxService.getReposiblePerson();
    this.activitySandboxService.getRewardparticipants();
  }

  /* select ActivityEvent[Start] */
  selectActivity(selTab) {
    this.actStreamDataService.resetActivityStream();
    this.actStreamDataService.resetPopUpBox();
    this.actStreamDataService.selectedWidget.selWctab = selTab;
    this.isValidated = true;
    this.activitySandboxService.resetData();
    if(selTab == 'task'){
      this.actStreamDataService.createTask.responsiblePerson = {
        responsiblePersonId: this.cookieService.get('userSlug'),
        responsiblePersonName: this.cookieService.get('userName')
      }
      this.actStreamDataService.createTask.approver = {
        approverName: this.cookieService.get('userName'),
        approverSlug: this.cookieService.get('userSlug')
      }
    } 
  }
  /* select ActivityEvent[end] */

  /* Get users list [Start] */
  initOrChangeparticipantsList(): void {
    this.activitySandboxService.getReposiblePerson();
  }
  /* Get users list [End]*/

  /* reset user list[Start] */
  resetUserList(): void {
    this.actStreamDataService.responsiblePersons.searchParticipantsTxt = '';
  }
  /*  reset user list[end] */

  /* reset user list[Start] */
  resetRewardParticipants(): void {
    this.actStreamDataService.rewardPersons.searchRewardParticipantsTxt = '';
  }
  /*  reset user list[end] */

  resetTaskUserList() {
    this.actStreamDataService.responsiblePersons.searchParticipantsTxt = '';
    this.activitySandboxService.getReposiblePerson();
  }

  /* Remove user from toUser list [Start] */
  removeUsers(user): void {
    let existingUsers = this.actStreamDataService.toUsers.toUsers.filter(
      part => part.userSlug === user.userSlug)[0];
    if (existingUsers) {
      let idx = this.actStreamDataService.toUsers.toUsers.indexOf(existingUsers);
      if (idx !== -1) this.actStreamDataService.toUsers.toUsers.splice(idx, 1);
    }
    let addUser = this.actStreamDataService.responsiblePersons.list.filter(
      part => part.slug === user.userSlug)[0];
    let idx = this.actStreamDataService.responsiblePersons.list.indexOf(addUser);
    this.actStreamDataService.responsiblePersons.list[idx].existing = false
  }
  /* Remove user from toUser list[end] */

  /* Remove Reward Participants list [Start]  */
  removeRewardParticipants(user): void {
    let existingUsers = this.actStreamDataService.createAppreciation.recipients.filter(
      part => part.userSlug === user.userSlug)[0];
    if (existingUsers) {
      let idx = this.actStreamDataService.createAppreciation.recipients.indexOf(existingUsers);
      if (idx !== -1) this.actStreamDataService.createAppreciation.recipients.splice(idx, 1);
    }
    let addUser = this.actStreamDataService.responsiblePersons.list.filter(
      part => part.slug === user.userSlug)[0];
    let idx = this.actStreamDataService.responsiblePersons.list.indexOf(addUser);
    this.actStreamDataService.responsiblePersons.list[idx].existing = false
  }
  /* Remove Reward Participants list [end]  */

  /* remove all employees[Start] */
  removeAllEmployees(): void {
    this.actStreamDataService.toUsers.toAllEmployee = false;
    this.actStreamDataService.toUsers.toUsers = [];
  }
  /* remove all employees[end] */

  /* select all employees[Start] */
  selectAllEmployees(): void {
    this.actStreamDataService.toUsers.toAllEmployee = true;
    this.actStreamDataService.toUsers.toUsers = [];
    this.userlistingdrop = false;
    this.activitySandboxService.getReposiblePerson();
  }
  /* select all employees[end] */

  /* Select users list [Start] */
  selectUser(users): void {
    let existingUsers = this.actStreamDataService.toUsers.toUsers.filter(
      part => part.userSlug === users.slug)[0];
    if (existingUsers) {
      // toast to handle already added participant
      return;
    }
    this.actStreamDataService.toUsers.toUsers.push({
      userSlug: users.slug,
      name: users.employee_name
    });
    users.existing = true;
    this.actStreamDataService.toUsers.toAllEmployee = false;
    this.actStreamDataService.responsiblePersons.searchParticipantsTxt = '';
    this.activitySandboxService.getReposiblePerson();
  }
  /* Select users list [End] */

  /* Select users list [Start] */
  selectrewardParticipants(Participants): void {
    let existingUsers = this.actStreamDataService.createAppreciation.recipients.filter(
      part => part.userSlug === Participants.slug)[0];
    if (existingUsers) {
      // toast to handle already added participant
      return;
    }
    this.actStreamDataService.createAppreciation.recipients.push({
      userSlug: Participants.slug,
      name: Participants.employee_name
    });
    Participants.existing = true;
    this.actStreamDataService.rewardPersons.searchRewardParticipantsTxt = '';
    this.activitySandboxService.getRewardparticipants();
  }
  /* Select users list [end] */

  /* Handling search and listing of res person[start] */
  initOrChangeResPrsnList(): void {
    this.activitySandboxService.getReposiblePerson();
  }
  /* Handling search and listing of res person[end] */

  /* select res person[start] */
  selectResp(users): void {
    this.userlistingdrop = false
    this.actStreamDataService.createTask.responsiblePerson = {
      responsiblePersonId: users.slug,
      responsiblePersonName: users.employee_name
    }
   }
  /* select res person[start] */

  /* Remove a resposible Prsn[Start] */
  removeRespPrsn(): void {
    this.actStreamDataService.createTask.responsiblePerson = {
      responsiblePersonId: '',
      responsiblePersonName: ''
    };
  }
  /* Remove a resposible Prsn[End] */

   /************************* message ************************* /
  /* Validating creating new message[Start] */
  validateNewMessage(): boolean {
    this.isValidated = true;
    if (!this.actStreamDataService.createMessage.msgTitle) this.isValidated = false;
    if (this.actStreamDataService.msgEditor.text == '') this.isValidated = false;
    if (this.actStreamDataService.toUsers.toUsers.length === 0 && !this.actStreamDataService.toUsers.toAllEmployee) this.isValidated = false;
    return this.isValidated;
  }
  /* Validating creating new message[end] */

  /* create new message [Start] */
  createNewMessage(): void {
    if (!this.validateNewMessage()) return;
    this.actStreamDataService.createMessage.action = 'create';
    this.actStreamDataService.createMessage.msgDesc = this.actStreamDataService.msgEditor.text;
    this.activitySandboxService.createNewMessage();
    this.userlistingdrop = false;
  }
  /* create new message [end] */

  /* update message [Start] */
  updateMessage(): void {
    if (!this.validateNewMessage()) return;
    this.actStreamDataService.createMessage.msgDesc = this.actStreamDataService.msgEditor.text;
    this.activitySandboxService.createNewMessage();
    this.userlistingdrop = false;
  }
  /* update message [end] */

  /*  cancel message[start]*/
  CancelMsg(): void {
    this.isValidated = true;
    this.activitySandboxService.resetData();
    this.activitySandboxService.fetchActivityStream();
  }
  /* cancel message[end] */
 /************************* message ************************* /

 /************************* task ************************* /
  /* validate new task[start] */
  validateNewTask(): boolean {
    this.isValidated = true;
    if (!this.actStreamDataService.createTask.title) this.isValidated = false;
    if (!this.actStreamDataService.msgEditor.text) this.isValidated = false;
    if (!this.actStreamDataService.createTask.endDate) this.isValidated = false;
    if (!this.actStreamDataService.createTask.responsiblePerson.responsiblePersonId) this.isValidated = false;
    return this.isValidated;
  }
  /* validate new task[end] */

  /* create new task[start] */
  createNewTask(): void {
    if (!this.validateNewTask()) return;
    if (this.actStreamDataService.createTask.showRepeatTaskSection === false) {
      this.actStreamDataService.createTask.repeat = null;
    }
    this.actStreamDataService.createTask.description = this.actStreamDataService.msgEditor.text;
    this.spinner.show();
    this.activitySandboxService.createNewTask();
  }
  /* create new task[end] */

  /* go to task moreoption[start] */
  goToTask(): void {
    this.actStreamDataService.createTask.description = this.actStreamDataService.msgEditor.text;
    this.actStreamDataService.showCreatetaskpopup.show = true;
  }
  /* go to task moreoption[end] */

  /* update task [Start] */
  updateTask(): void {
    if (!this.validateNewTask()) return;
    this.actStreamDataService.createTask.description = this.actStreamDataService.msgEditor.text;
    this.activitySandboxService.updateTask();
    this.userlistingdrop = false;
  }
  /* update task [end] */

  /* cancel new task[start] */
  cancelTask(): void {
    this.isValidated = true;
    this.activitySandboxService.resetData();
    this.activitySandboxService.fetchActivityStream();
  }
  /* cancel new task[start] */
/************************* task ************************* /
 
/************************* event ************************* /
  /* set reminder for event[start] */
  setReminder() {
    if (this.actStreamDataService.createEvent.reminderOpt) {
      this.reminderDisabled = !this.reminderDisabled;
    }
    else {
      this.actStreamDataService.createEvent.reminder.type = '';
      this.actStreamDataService.createEvent.reminder.count = '';
      this.reminderDisabled = true;
    }
  }
  /* set reminder for event[end] */

  /* give type for event[start] */
  setType(type) {
    this.actStreamDataService.createEvent.reminder.type = type;
    this.typeDd = false;
  }
  /* give type for event[end] */

  /* set repeate option for event[start] */
  setRepeateOpt(RepeateOpt) {
    this.actStreamDataService.createEvent.eventRepeat = RepeateOpt;
    this.repDd = false;
  }
  /* set repeate option for event[end] */

  /* set availability option for event[start] */
  setAvaOpt(AvaOpt) {
    this.actStreamDataService.createEvent.eventAvailability = AvaOpt;
    this.AvaDd = false;
  }
  /* set availability option for event[end] */

  /* set important option for event[start] */
  setImpOpt(ImpOpt) {
    this.actStreamDataService.createEvent.eventImportance = ImpOpt;
    this.impDd = false;
  }
  /* set important option for event[end] */

  /* close more options from event[start] */
  clearMoreOption() {
    this.actStreamDataService.createEvent.eventRepeat = null;
    this.actStreamDataService.createEvent.eventAvailability = null;
    this.actStreamDataService.createEvent.eventImportance = null;
    this.actStreamDataService.moreOption.show = false;
  }
  /* close more options from event[end] */

  /* validate new event[start] */
  validateNewEvent(): boolean {
    this.isValidated = true;
    if (!this.actStreamDataService.createEvent.eventTitle) this.isValidated = false;
    if (!this.actStreamDataService.msgEditor.text) this.isValidated = false;
    if (!this.actStreamDataService.createEvent.eventStart) this.isValidated = false;
    if (!this.actStreamDataService.createEvent.eventEnd) this.isValidated = false;
    if (!this.actStreamDataService.createEvent.eventLocation) this.isValidated = false;
    if (this.actStreamDataService.toUsers.toUsers.length === 0 && !this.actStreamDataService.toUsers.toAllEmployee) this.isValidated = false;
    if (this.actStreamDataService.createEvent.reminderOpt === true) {
      if (this.actStreamDataService.createEvent.reminder.count === '') this.isValidated = false;
      if (this.actStreamDataService.createEvent.reminder.type === '') this.isValidated = false;
    }
    return this.isValidated;
  }
  /* validate new event[end] */

  /* Create event [Start]*/
  createNewEvent(): void {
    if (!this.validateNewEvent()) return;
    this.actStreamDataService.createEvent.action = 'create'
    this.actStreamDataService.createEvent.eventDesc = this.actStreamDataService.msgEditor.text;
    if (!this.actStreamDataService.createEvent.reminderOpt) {
      this.actStreamDataService.createEvent.reminder.type = '';
      this.actStreamDataService.createEvent.reminder.count = '';
    }
    if (!this.actStreamDataService.moreOption.show) {
      this.actStreamDataService.createEvent.eventAvailability = null;
      this.actStreamDataService.createEvent.eventRepeat = null;
      this.actStreamDataService.createEvent.eventImportance = null;
    }
    this.actStreamDataService.createEvent.eventStart = this.utilityService.convertToUnix(this.actStreamDataService.createEvent.eventStart);
    this.actStreamDataService.createEvent.eventEnd = this.utilityService.convertToUnix(this.actStreamDataService.createEvent.eventEnd);
    this.activitySandboxService.createNewEvent();
    this.userlistingdrop = false;
  }
  /* Create event [end]*/

  /* Update Event [Start] */
  updateEvent(): void {
    if (!this.validateNewEvent()) return;
    this.actStreamDataService.createEvent.action = 'update';
    this.actStreamDataService.createEvent.eventDesc = this.actStreamDataService.msgEditor.text;
    if (!this.actStreamDataService.createEvent.reminderOpt) {
      // this.actStreamDataService.createEvent.reminder = [];
      // this.actStreamDataService.createEvent.reminder.push({ 'type': this.type, 'count': this.count })
      this.actStreamDataService.createEvent.reminder.type = '';
      this.actStreamDataService.createEvent.reminder.count = '';
    }
    this.actStreamDataService.createEvent.eventStart = this.utilityService.convertToUnix(this.actStreamDataService.createEvent.eventStart);
    this.actStreamDataService.createEvent.eventEnd = this.utilityService.convertToUnix(this.actStreamDataService.createEvent.eventEnd);
    this.activitySandboxService.createNewEvent();
    this.userlistingdrop = false;
  }
  /* Update Event [end] */

  /*  cancel create event[start]*/
  cancelEvent(): void {
    this.isValidated = true;
    this.userlistingdrop = false;
    this.actStreamDataService.createEvent.reminder.type = '';
    this.actStreamDataService.createEvent.reminder.count = '';
    this.actStreamDataService.createEvent.reminderOpt = false;
    this.activitySandboxService.resetData();
    this.activitySandboxService.fetchActivityStream();
  }
  /* cancel create event[end] */
/************************* event ************************* /

/************************* Announcement ************************* /
  /* validate new Announcement[start] */
  validateNewAnnouncement(): boolean {
    this.isValidated = true;
    if (!this.actStreamDataService.createAnnouncement.ancTitle) this.isValidated = false;
    if (!this.actStreamDataService.msgEditor.text) this.isValidated = false;
    if (this.actStreamDataService.toUsers.toUsers.length === 0 && !this.actStreamDataService.toUsers.toAllEmployee) this.isValidated = false;
    return this.isValidated;
  }
  /* validate new Announcement[end] */

  /* create new Announcement[start] */
  createNewAnnouncement(): void {
    if (!this.validateNewAnnouncement()) return;
    this.actStreamDataService.createAnnouncement.action = 'create';
    this.actStreamDataService.createAnnouncement.ancDesc = this.actStreamDataService.msgEditor.text;
    this.activitySandboxService.createNewAnnouncement();
    this.userlistingdrop = false;
  }
  /* create new Announcement[end] */

  /* update announcement [Start] */
  updateAnnouncement(): void {
    if (!this.validateNewAnnouncement()) return;
    this.actStreamDataService.createAnnouncement.ancDesc = this.actStreamDataService.msgEditor.text;
    this.activitySandboxService.createNewAnnouncement();
    this.userlistingdrop = false;
  }
 /* update announcement [end] */

  /* cancel Announcement[start] */
  cancelAnnouncement(): void {
    this.isValidated = true;
    this.activitySandboxService.resetData();
    this.activitySandboxService.fetchActivityStream();
  }
  /* cancel Announcement[end] */
  /************************* Announcement ************************* /

  /************************* Poll ************************* /
  /* add more answer option for poll question[start] */
  addAnsOptions(index) {
    this.actStreamDataService.createPoll.pollQuestions[index].answerOptions.push({ pollOptId: null, pollOption: '' });
  }
  /* add more answer option for poll question[end] */

  /* add more question in poll[start] */
  addpollQuestions() {
    this.actStreamDataService.createPoll.pollQuestions.push({
      action: 'create', pollQuestionId: null, pollQuestion: '',
      allowMultipleChoice: false, answerOptions: [{
        pollOptId: null, pollOption: ''
      }, {
        pollOptId: null, pollOption: ''
      }]
    })
  }
  /* add more question in poll[end] */

  /* delete selected poll answer option[start] */
  deleteOptions(answerOptions) {
    for (let i = 0; i < this.actStreamDataService.createPoll.pollQuestions.length; i++) {
      for (let x = 0; x < this.actStreamDataService.createPoll.pollQuestions[i].answerOptions.length; x++) {
        let option = this.actStreamDataService.createPoll.pollQuestions[i].answerOptions.filter(
          part => part.pollOption === answerOptions.pollOption)[0];
        if (option) {
          let idx = this.actStreamDataService.createPoll.pollQuestions[i].answerOptions.indexOf(option);
          if (idx !== -1) this.actStreamDataService.createPoll.pollQuestions[i].answerOptions.splice(idx, 1);
        }
      }
    }
  }
  /* delete selected answer option[end] */

  /* delete selected poll question [start] */
  deletePollQuestions(qust) {
    let qst = this.actStreamDataService.createPoll.pollQuestions.filter(
      part => part.pollQuestion === qust.pollQuestion)[0];

    if (qst) {
      let idx = this.actStreamDataService.createPoll.pollQuestions.indexOf(qst);
      if (idx !== -1) this.actStreamDataService.createPoll.pollQuestions.splice(idx, 1);
    }
  }
  /* delete selected poll question[end] */

  /* validate new poll[start] */
  validateNewPoll(): boolean {
    this.isValidated = true;
    if (!this.actStreamDataService.createPoll.pollTitle) this.isValidated = false;
    if (!this.actStreamDataService.msgEditor.text) this.isValidated = false;
    for (let i = 0; i < this.actStreamDataService.createPoll.pollQuestions.length; i++) {
      if (!this.actStreamDataService.createPoll.pollQuestions[i].pollQuestion) this.isValidated = false;
      for (let j = 0; j < this.actStreamDataService.createPoll.pollQuestions[i].answerOptions.length; j++) {
        if (!this.actStreamDataService.createPoll.pollQuestions[i].answerOptions[j].pollOption) this.isValidated = false;
      }
    }
    return this.isValidated;
  }
  /* validate new poll[end] */

  /* create new poll[start] */
  createNewPoll() {
    if (!this.validateNewPoll()) return;
    this.actStreamDataService.createPoll.action = 'create';
    this.actStreamDataService.createPoll.pollDesc = this.actStreamDataService.msgEditor.text;
    this.activitySandboxService.createNewPoll();
    this.userlistingdrop = false;
  }
  /* create new poll[end] */

  /* update poll [Start] */
  updatePoll(): void {
    if (!this.validateNewPoll()) return;
    this.actStreamDataService.createPoll.pollDesc = this.actStreamDataService.msgEditor.text;
    this.activitySandboxService.createNewPoll();
    this.userlistingdrop = false;
  }
  /* update poll [end] */

  /* cancel Poll[start] */
  cancelPoll() {
    this.isValidated = true;
    this.activitySandboxService.resetData();
    this.activitySandboxService.fetchActivityStream();
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
  }
  /* cancel Poll[end] */

  /************************* Poll ************************* /
   
  /************************* Appreciation ************************* /
  /* search appriciation rticipants[start] */
  initOrChangeRewardparticipantsList(): void {
    this.activitySandboxService.getRewardparticipants(); // Using the same API for getting responsible person
  }
  /* search appriciation rticipants[end] */

  /* validate new Appreciation[start] */
  validateNewAppreciation(): boolean {
    this.isValidated = true;
    if (!this.actStreamDataService.createAppreciation.aprTitle) this.isValidated = false;
    if (!this.actStreamDataService.msgEditor.text) this.isValidated = false;
    if (this.actStreamDataService.toUsers.toUsers.length === 0 && !this.actStreamDataService.toUsers.toAllEmployee) this.isValidated = false;
    if (this.actStreamDataService.createAppreciation.recipients.length === 0) this.isValidated = false;
    if (this.actStreamDataService.createAppreciation.aprHasDisplayDuration) {
      if (!this.actStreamDataService.createAppreciation.aprDisplayStart) this.isValidated = false;
      if (!this.actStreamDataService.createAppreciation.aprDisplayEnd) this.isValidated = false;
    }

    return this.isValidated;
  }
  /* validate new Appreciation[end] */

  /* create new Appreciation[start] */
  createNewAppreciation(): void {
    if (!this.validateNewAppreciation()) return;
    this.actStreamDataService.createAppreciation.action = 'create';
    this.actStreamDataService.createAppreciation.aprDesc = this.actStreamDataService.msgEditor.text;
    if (this.actStreamDataService.createAppreciation.aprHasDisplayDuration) {
      this.actStreamDataService.createAppreciation.aprDisplayStart = this.utilityService.convertToUnix(this.actStreamDataService.createAppreciation.aprDisplayStart);
      this.actStreamDataService.createAppreciation.aprDisplayEnd = this.utilityService.convertToUnix(this.actStreamDataService.createAppreciation.aprDisplayEnd);
    }
    this.spinner.show();
    this.activitySandboxService.createNewAppreciation();
    this.userlistingdrop = false;
  }
  /* create new Appreciation[end] */

  /* update Appreciation [Start] */
  updateAppreciation(): void {
    if (!this.validateNewAppreciation()) return;
    this.actStreamDataService.createAppreciation.aprDesc = this.actStreamDataService.msgEditor.text;
    if (this.actStreamDataService.createAppreciation.aprHasDisplayDuration) {
      this.actStreamDataService.createAppreciation.aprDisplayStart = this.utilityService.convertToUnix(this.actStreamDataService.createAppreciation.aprDisplayStart);
      this.actStreamDataService.createAppreciation.aprDisplayEnd = this.utilityService.convertToUnix(this.actStreamDataService.createAppreciation.aprDisplayEnd);
    }
    this.activitySandboxService.createNewAppreciation();
    this.userlistingdrop = false;
  }
  /* update Appreciation [end] */

  /* cancel Appreciation[start] */
  cancelAppreciation() {
    this.isValidated = true;
    this.activitySandboxService.resetData();
    this.activitySandboxService.fetchActivityStream();
  }
  /* cancel Appreciation[end] */

  closeOverlay() {
    this.AvaDd = false;
    this.repDd = false;
    this.impDd = false;
  }
}
