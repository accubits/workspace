import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { Message } from '../../../../node_modules/@angular/compiler/src/i18n/i18n_ast';

@Injectable()
export class ActStreamDataService {

  constructor(
    private cookieService: CookieService
  ) { }

  /* Init Data Models for activityStram module[Start] */
  actStreamModels = {

    eventShow: {
      show : false
    },
    eventView: {
      show : false
    },
    selectedWidget: {
      selWctab: 'message',
    },

    fetchActivityStream: {
      selectedTab: '',
      page: 1,
      perPage: 10,
      total: null,
      to: null,
      streamData: [],
      scrollArrow: false,
    },

    activityCreatePopUp: {
      show: false
    },

    activityList: {
      show: true
    },
 
    msgEditor: {
      text: ''
    },

    responsiblePersons: {
      list: [],
      searchParticipantsTxt: '',
     
    },

    rewardPersons: {
      list: [],
      searchRewardParticipantsTxt: '',
    },

    toUsers: {
      toAllEmployee: false,
      toUsers: [],
    },

    deleteMessage: {
      msg: ''
    },

    eventMessage: {
      msg: ''
    },

    createMessage: {
      orgSlug: this.cookieService.get('orgSlug'),
      action: '',
      msgSlug: '',
      msgTitle: '',
      msgDesc: '',
      popupMsg: '',
    },

    createTask: {
      action: '',
      showRepeatTaskSection: false,
      taskSlug: '',
      title: '',
      description: '',
      priority: false,
      favourite: false,
      selectedPriority: '',
      startDate: null,
      endDate: null,
      reminder: null,
      responsiblePerson: {
        responsiblePersonName: this.cookieService.get('userName'),
        responsiblePersonId: this.cookieService.get('userSlug')
      },
      checklists: [],
      parentTask: {
        parentTaskSlug: "",
        parentTaskTitle: ""
      },
      approver: {
        approverName: this.cookieService.get('userName'),
        approverSlug: this.cookieService.get('userSlug')
      },
      participantIds: [],
      assignees: [],
      fileList: [],
      responsiblePersonCanChangeTime: false,
      approveTaskCompleted: false,
      repeat: {
        repeat_type: 'week',
        repeat_every: 1,
        week: {
            Sunday: false,
            Monday: false,
            Tuesday: false,
            Wednesday: false,
            Thursday: false,
            Friday: false,
            Saturday: false
        },
        ends: {
            never: false,
            on: null,
            after: ""
        }
    },
      isTemplate: false,
      taskEndOption: 'nemsgDescer'
    },

    createEvent: {
      index: '',
      orgSlug: this.cookieService.get('orgSlug'),
      eventCreatorSlug: '',
      eventCreatorEmail: '',
      eventCreatorName: '',
      action: '',
      eventCreatedAt: null,
      eventSlug: '',
      eventTitle: '',
      eventDesc: "",
      eventStart: null,
      eventEnd: null,
      eventAllDay: false,
      reminderOpt: false,
      reminder: {
        type: '',
        count: ''
      },
      eventLocation: '',
      eventRepeat: 'Never',
      eventAvailability: 'Occupied',
      eventImportance: 'Low',
      eventDetails: null,
      eventMembers: [],
      userResponse: false,
      eventResponse: '',
      loggedUser: '',
      start: false,
      onGoing: false,
      end: false,
      goingCount: null,
      declineCount: null,
      eventToAllEmployee: false,
      
    },
    
    createAnnouncement: {
      orgSlug: this.cookieService.get('orgSlug'),
      action: '',
      ancSlug: '',
      ancTitle: '',
      ancDesc: '',
      toAllEmployee: false,
      hasRead: false
    },

    createPoll: {
      orgSlug: this.cookieService.get('orgSlug'),
      action: '',
      pollSlug: null,
      pollTitle: '',
      pollDesc: '',
      status: 'Open',
      pollQuestions: [{
        action: 'create',
        pollQuestionId: null,
        pollQuestion: '',
        allowMultipleChoice: false,
        answerOptions: [{
          pollOptId: null,
          pollOption: ''
        },
        {
          pollOptId: null,
          pollOption: ''
        }]
      }],
      toAllEmployee: false,
      pollQuestionsAnswers: [],
    },

    createAppreciation: {
      orgSlug: this.cookieService.get('orgSlug'),
      action: '',
      aprSlug: null,
      aprTitle: '',
      aprDesc: '',
      status: 'Show',
      recipients: [],
      aprHasDisplayDuration: false,
      aprDisplayStart: null,
      aprDisplayEnd: null
    },

    taskWidgetDetails:{
      details:{
        overview:{
          active: '',
          overdue: '',
          priority: '',
          upcomingDeadline: '',
        },
        birthdays: [],
        task: []
      },
      taskDateRange:'sevenDays'
    },

    CommentsAndResponses: {
      imageUrl: '',
      getComentsAndResponse: [],
      responseCount: null,
      commentCount: '',
      liked: false,
      getMessageComents: [],
      getMessageResponse: [],
      getCommentReply: [],
      responseSlug: null,
      commentSlug: null,
      parentCommentSlug: null,
      commentResponseSlug: null,
      commentTxt: '',
      action: 'create',
      taskCommentLike: false,
    },
    taskRunManagement: {
      selectedAll: false,
      selectedTaskIds: [],
      selectedTaskIndex:1,
      status: '',
      action:'single',
   },

    taskTemplates: {
      lists: [],
      selectedTemplateSlug: ''
    },

    parentTasks: {
      list: [],
      searchText: ''
    },

    addDisplayPeriod: {
      show: false,
    },

    moreOption: {
      show: false,
    },
   
    comments: {
      show: false
    },

    reply: {
      show: false
    },

    pollList: {
      show: false
    },

    pollSubmit: {
      show: false
    },

    pollClose: {
      show: false
    },

    userCount: {
      show: false
    },

    eventStatusPopUp: {
      show: false
    },

    deletePopUp: {
      show: false
    },

    deleteReplyPopUp: {
      show: false
    },


    deleteCommentPopUp: {
      show: false
    },

    optionBtn: {
      show: false
    },

    cmtOptionBtn: {
      show: false
    },

    replyOption: {
      show: false
    },

    goinguserCount: {
      show: false
    },

    hasReadCount: {
      show: false
    },

    recipientCount: {
      show: false
    },

    eventStatus: {
      show: false
    },

    showCreatetaskpopup: {
      show: false
    },
  }
  /* Init Data Models for activityStram module[End] */

  eventShow = {...this.actStreamModels.eventShow};
  eventView = {...this.actStreamModels.eventView};
  selectedWidget = { ...this.actStreamModels.selectedWidget };
  fetchActivityStream = { ...this.actStreamModels.fetchActivityStream };
  msgEditor = { ...this.actStreamModels.msgEditor };
  responsiblePersons = { ...this.actStreamModels.responsiblePersons };
  rewardPersons = { ...this.actStreamModels.rewardPersons };
  toUsers = { ...this.actStreamModels.toUsers };
  createMessage = { ...this.actStreamModels.createMessage };
  createTask = { ...this.actStreamModels.createTask };
  createEvent = { ...this.actStreamModels.createEvent };
  createAnnouncement = { ...this.actStreamModels.createAnnouncement };
  createPoll = { ...this.actStreamModels.createPoll };
  createAppreciation = { ...this.actStreamModels.createAppreciation };
  CommentsAndResponses = { ...this.actStreamModels.CommentsAndResponses };
  activityCreatePopUp = { ...this.actStreamModels.activityCreatePopUp };
  activityList = { ...this.actStreamModels.activityList };
  pollList = { ...this.actStreamModels.pollList };
  pollSubmit = { ...this.actStreamModels.pollSubmit };
  pollClose = { ...this.actStreamModels.pollClose };
  userCount = { ...this.actStreamModels.userCount };
  eventStatusPopUp = { ...this.actStreamModels.eventStatusPopUp };
  comments = { ...this.actStreamModels.comments };
  reply = { ...this.actStreamModels.reply };
  goinguserCount = { ...this.actStreamModels.goinguserCount };
  hasReadCount = { ...this.actStreamModels.hasReadCount };
  recipientCount = { ...this.actStreamModels.recipientCount };
  eventStatus = { ...this.actStreamModels.eventStatus };
  moreOption = { ...this.actStreamModels.moreOption };
  addDisplayPeriod = { ...this.actStreamModels.addDisplayPeriod };
  taskTemplates = { ...this.actStreamModels.taskTemplates };
  parentTasks = { ...this.actStreamModels.parentTasks };
  showCreatetaskpopup = { ...this.actStreamModels.showCreatetaskpopup };
  optionBtn = { ...this.actStreamModels.optionBtn };
  cmtOptionBtn = { ...this.actStreamModels.cmtOptionBtn };
  replyOption = { ...this.actStreamModels.replyOption };
  deletePopUp = { ...this.actStreamModels.deletePopUp };
  deleteCommentPopUp = { ...this.actStreamModels.deleteCommentPopUp };
  deleteReplyPopUp = { ...this.actStreamModels.deleteReplyPopUp };
  deleteMessage = { ...this.actStreamModels.deleteMessage };
  eventMessage = { ...this.actStreamModels.eventMessage };
  taskWidgetDetails = { ...this.actStreamModels.taskWidgetDetails};
  taskRunManagement = { ...this.actStreamModels.taskRunManagement}

  resetTaskRunManagement(): void {
    this.actStreamModels.taskRunManagement.selectedTaskIds = [];
    this.taskRunManagement = { ...this.actStreamModels.taskRunManagement };
}

  resetActivityStream(): void {
    this.createPoll = { ...this.actStreamModels.createPoll };
    this.createMessage = { ...this.actStreamModels.createMessage };
    this.createTask = { ...this.actStreamModels.createTask };
    this.createEvent = { ...this.actStreamModels.createEvent };
    this.createAnnouncement = { ...this.actStreamModels.createAnnouncement };
    this.createAppreciation = { ...this.actStreamModels.createAppreciation };
    this.CommentsAndResponses = { ...this.actStreamModels.CommentsAndResponses };
    this.msgEditor = { ...this.actStreamModels.msgEditor };
    this.deleteMessage = { ...this.deleteMessage };
  }

  resetPopUpBox(): void {
    this.activityCreatePopUp = { ...this.actStreamModels.activityCreatePopUp };
    this.eventShow = { ...this.actStreamModels.eventShow };
    this.eventView = { ...this.actStreamModels.eventView };
    this.userCount = { ...this.actStreamModels.userCount };
    this.goinguserCount = { ...this.actStreamModels.goinguserCount };
    this.hasReadCount = { ...this.actStreamModels.hasReadCount };
    this.recipientCount = { ...this.actStreamModels.recipientCount };
    this.eventStatus = { ...this.actStreamModels.eventStatus };
    this.moreOption = { ...this.actStreamModels.moreOption };
    this.addDisplayPeriod = { ...this.actStreamModels.addDisplayPeriod };
    this.taskTemplates = { ...this.actStreamModels.taskTemplates };
    this.showCreatetaskpopup = { ...this.actStreamModels.showCreatetaskpopup };
    this.optionBtn = { ...this.actStreamModels.optionBtn };
    this.cmtOptionBtn = { ...this.actStreamModels.cmtOptionBtn };
    this.deletePopUp = { ...this.actStreamModels.deletePopUp };
    this.replyOption = { ...this.actStreamModels.replyOption };
    this.deleteCommentPopUp = { ...this.actStreamModels.deleteCommentPopUp };
    this.deleteReplyPopUp = { ...this.actStreamModels.deleteReplyPopUp };
  }
}
