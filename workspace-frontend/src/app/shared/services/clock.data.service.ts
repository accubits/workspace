import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';



@Injectable()
export class ClockDataService {

  constructor(private cookieService: CookieService) { }

  clockModel = {
    clockManagement: {
      clockIn: false,
      clockOut: true,
      pause: false,
      resume: false,
      workReport: false,
      stratTime: 0,
      editWork: false,
      showTimer:false,
      showWorkReport:false,
    },

    clockTimer: {
      days: 0,
      hours: 0,
      minutes: 0,
      seconds: 0,
      tick_count: 0,
      timer: 0
    },

    clockStatus: {
      startTime: null,
      totalBreakTime: "",
      totalWorkingTime: "",
      clockStatusButtons: {
        clockIn: false,
        pause: false,
        clockContinue: false,
        clockOut: false,
        editWorkingTime: false,
        clockResume: false,
      },
      currentStatusName: 'clockOut',
      isWorkReportPrompt:false,
      currentStatusId: null,
      isPreviousDayClockout: true,
      lastRecordedTime:null
    },

    clockInput: {
      orgSlug: this.cookieService.get('orgSlug'),
      currentTime: null,
      status: '',
      note: '',
      lastRecordedTime:null
      
    },
    alCheck:{
        show:false
    },

    previosDayInput: {
      "orgSlug": this.cookieService.get('orgSlug'),
      "currentTime": null,
      "clockOutTime": null,
      "note": ""
    },

    editWorkTime: {
      "orgSlug": this.cookieService.get('orgSlug'),
      "workDate": null,
      "startTime": null,
      "endTime": null,
      "break": {
        "hour": null,
        "minute": null
      },
      "note": null
    },

    clockReportdata:{
       "tasks": [],
        "events": [],
        "report":'',
        "to": [],
        "dates": {
          "startDate": null,
          "endDate": null
        },
     },

    clockCurrentstatus: {
      orgSlug: this.cookieService.get('orgSlug'),
      currentTime: null
    },

    getTasksClockReportInput: {
      orgSlug: this.cookieService.get('orgSlug'),
      q: ''
    },

    tasksClockReport: {
      "current_page": 1,
      "from": 1,
      "last_page": 1,
      "per_page": 10,
      "to": 1,
      "total": 1,
      "tasks": []
    },

    getEventsClockReportInput: {
      orgSlug: this.cookieService.get('orgSlug'),
      q: ''
    },

    eventsClockReport:{
      "current_page": 1,
      "from": 1,
      "last_page": 1,
      "per_page": 10,
      "to": 1,
      "total": 1,
      "events": [],
      
    },

    sendReportInput:{
      "orgSlug":this.cookieService.get('orgSlug'),
      "fromDate": null,
      "toDate": null,
      "toUser": "",
      "reportDescription": "",
      "tasks": [],
      "events": [],
      "isSend": false
    },

    fetchWorkDayInput:{
      "orgSlug":this.cookieService.get('orgSlug'),
      "selectDate": null
    }

  }

  clockManagement = { ...this.clockModel.clockManagement };
  clockStatus = { ...this.clockModel.clockStatus };
  clockInput = { ...this.clockModel.clockInput };
  alCheck = {...this.clockModel.alCheck};
  clockCurrentstatus = { ...this.clockModel.clockCurrentstatus };
  fetchWorkDayInput = { ...this.clockModel.fetchWorkDayInput };
  previosDayInput = { ...this.clockModel.previosDayInput };
  clockTimer = { ...this.clockModel.clockTimer };
  editWorkTime = { ...this.clockModel.editWorkTime };
  clockReportdata = { ...this.clockModel.clockReportdata };
  getTasksClockReportInput = { ...this.clockModel.getTasksClockReportInput };
  getEventsClockReportInput = { ...this.clockModel.getEventsClockReportInput };
  tasksClockReport = { ...this.clockModel.tasksClockReport };
  eventsClockReport = { ...this.clockModel.eventsClockReport };
  sendReportInput = { ...this.clockModel.sendReportInput };

  resetClockInput(): void {
    //this.clockInput = { ...this.clockModel.clockInput };
     this.clockInput.note = '';
  }

  resetClockManagement(): void {
    this.clockManagement = { ...this.clockModel.clockManagement };
  }

  /* Reset clock status */
  resetClockStatus(): void {
    this.clockStatus = { ...this.clockModel.clockStatus };
  }

  /* Reset clock status */
  resetEditWorkTime(): void {
    this.clockModel.editWorkTime.break.hour = null;
    this.clockModel.editWorkTime.break.minute = null;
    this.editWorkTime = { ...this.clockModel.editWorkTime };
  }

  /* Reset clock input */
  resetPreviousDay(): void {
    this.previosDayInput = { ...this.clockModel.previosDayInput };
  }

  /*reset Send Report */
  resetSendReport(): void {
    this.clockModel.sendReportInput.tasks = [];
    this.clockModel.sendReportInput.events = [];
    this.sendReportInput = { ...this.clockModel.sendReportInput };
  }
}
