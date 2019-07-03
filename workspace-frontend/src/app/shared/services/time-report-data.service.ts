import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';

@Injectable()
export class TimeReportDataService {

  constructor(
    private cookieService: CookieService
  ) { }

  /* Init Data Models for time-report module[Start] */
  timeReportModels = {

    workTimeDetailPop:{
      show: false
    },
    filterPopup: {
      show: false
    },
    workTimeFilterPopup: {
      show: false
    },
    workTimeDetails: {
      show: false
    },
    workReportDetails: {
      show: false
    },

    createReport: {
      show: false
    },

    showDetails: {
      show: false
    },

    getWorkTimeInput: {
      orgSlug: this.cookieService.get('orgSlug'),
      monthYear: {
        month: null,
        year: null
      },
      page: 1,
      perPage: 10,
      filterBy: {

      }

    },

    departmentLists: {
      "current_page": 1,
      "from": 1,
      "last_page": 1,
      "per_page": 10,
      "to": 1,
      "total": 1,
      "departments": [
        {
          "departmentSlug": "",
          "departmentName": "",
          "orgSlug": "",
          "parentDepartmentSlug": null,
          "rootDepartmentSlug": null
        },
       ]
    },

    getWorkReportInput: {
      "orgSlug": this.cookieService.get('orgSlug'),
      "year": 2018
    },

    workTimeReport: {
      current_page: 1,
      from: 1,
      last_page: 1,
      per_page: 10,
      to: null,
      total: null,
      workTime: [{
        slug: '',
        departmentName: '',
        users: [
          {
            name: '',
            userSlug: '',
            orgDepartmentSlug: '',
            master_slug: '',
            totalWorkedHours: null,
            totalWorkedMinutes: null,
            isHeadOfDepartment: false,
            workDayReport: [{
              day: null,
              hours: null,
              minutes: null,
              reportSlug: null,
              confirm: false,
              absent: {}
            }],
            totalWorkedDays: null
          },
        ]
      }
      ]
    },

    getSelectedWorkTimeInput: {
      "orgSlug": this.cookieService.get('orgSlug'),
      "departmentSlug": "",
      "reportSlug": ""
    },

    selectedWorkTimeDetails: {
      "date": null,
      "reportFrom": {
        "slug": "",
        "name": "",
        "image" : "",
      },
      "reportTo": {
        "slug": "",
        "name": "",
        "image" : "",
      },
      "duration": {
        "hours": null,
        "minutes": null
      },
      "startedOn": null,
      "changedFrom": null,
      "reason": null,
      "endedOn": null,
      "isReportConfirmed": false,
      "isConfirmReportButton":false
    },

    workReportData: {
      workReport:
        [{
          "slug": "",
          "departmentName": "",

          "users": [{
            "userSlug": "",
            "userName":"",
            "orgDepartmentSlug": "",
            "totalReports": null,
            "unconfirmed": null,
            "scoreRatings": {
              "excellent": null,
              "positive": null,
              "negative": null
            },
            "reportTimeline": [{
              "month": "Jan",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }, {
              "month": "Feb",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }, {
              "month": "Mar",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }, {
              "month": "Apr",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }, {
              "month": "May",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }, {
              "month": "Jun",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }, {
              "month": "Jul",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }, {
              "month": "Aug",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }, {
              "month": "Sep",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }, {
              "month": "Oct",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }, {
              "month": "Nov",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }, {
              "month": "Dec",
              "startDate": null,
              "endDate": null,
              "reportTitle": null
            }]
          }]
        }]
    },

    detailedWorkReportInput: {
      reportSlug: ''
    },

    // selectedWorkReportDetails: {
    //   "reportPeriod": {
    //     "fromDate": null,
    //     "toDate": null
    //   },
    //   "reportFrom": "",
    //   "reportTo": "",
    //   "scoreGiven": [],
    //   "reportTtile": "",
    //   "tasks": [
    //     {
    //       "title": ""
    //     },
    //   ],
    //   "events": [
    //     {
    //       "eventSlug": "",
    //       "eventTitle": "",
    //       "eventStartDate": null,
    //       "eventEndDate": null,
    //       "location": ""
    //     }

    //   ],
    //   "scores": [
    //     {
    //       "scoreName": "",
    //       "scoreDisplayName": ""
    //     },

    //   ]
    // },

    selectedWorkReportDetails: {
      "isReportConfirmed": false,
      "confirmButton":false,
      "overallTaskScore":'',
      "reportPeriod": {
        "fromDate": null,
        "toDate": null
      },
      "reportSlug":'',
      "reportFrom": {
        "image":'',
        "name":''
      },
      "reportTo": {
        "image":'',
        "name":''
      },
      "scoreGiven": [],
      "reportTitle": "",
      "tasks": [
        {
          "title": ""
        },
      ],
      "events": [
        {
          "eventSlug": "",
          "eventTitle": "",
          "eventStartDate": null,
          "eventEndDate": null,
          "location": ""
        }

      ],
      "scores": [
        {
          "scoreName": "",
          "scoreDisplayName": ""
        },

      ]
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

    eventsClockReport: {
      "current_page": 1,
      "from": 1,
      "last_page": 1,
      "per_page": 10,
      "to": 1,
      "total": 1,
      "events": [],

    },

    sendReportInput: {
      "orgSlug": this.cookieService.get('orgSlug'),
      "fromDate": null,
      "toDate": null,
      "toUser": "",
      "reportDescription": "",
      "tasks": [],
      "events": [],
      "isSend": false
    },

    clockReportdata: {
      "tasks": [],
      "events": [],
      "to": [],
      "dates": {
        "startDate": null,
        "endDate": null
      },
      "report": "",
    },

    absentDetails: {
      absentSlug: null,
      absentUser: null,
      absentUserName: '',
      absentUserImg: '',
      absentStartsOn: null,
      startsOnHalfDay: false,
      absentEndsOn: null,
      endsOnHalfDay: false,
      leaveTypeName: '',
      reason: '',
      leaveTypeColorCode: ''
    },


  }

  absentDetails = { ...this.timeReportModels.absentDetails};
  workTimeDetailPop = { ...this.timeReportModels.workTimeDetailPop};
  showDetails = { ...this.timeReportModels.showDetails};
  filterPopup = { ... this.timeReportModels.filterPopup };
  workTimeFilterPopup = { ... this.timeReportModels.workTimeFilterPopup };
  workTimeDetails = { ... this.timeReportModels.workTimeDetails };
  createReport = { ... this.timeReportModels.createReport };
  workReportDetails = { ... this.timeReportModels.workReportDetails };
  getWorkTimeInput = { ... this.timeReportModels.getWorkTimeInput };
  getSelectedWorkTimeInput = { ... this.timeReportModels.getSelectedWorkTimeInput };
  selectedWorkTimeDetails = { ... this.timeReportModels.selectedWorkTimeDetails };
  workTimeReport = { ... this.timeReportModels.workTimeReport };
  getWorkReportInput = { ... this.timeReportModels.getWorkReportInput };
  workReportData = { ... this.timeReportModels.workReportData };
  detailedWorkReportInput = { ... this.timeReportModels.detailedWorkReportInput };
  selectedWorkReportDetails = { ... this.timeReportModels.selectedWorkReportDetails };
  clockReportdata = { ...this.timeReportModels.clockReportdata };
  getTasksClockReportInput = { ...this.timeReportModels.getTasksClockReportInput };
  getEventsClockReportInput = { ...this.timeReportModels.getEventsClockReportInput };
  tasksClockReport = { ...this.timeReportModels.tasksClockReport };
  eventsClockReport = { ...this.timeReportModels.eventsClockReport };
  sendReportInput = { ...this.timeReportModels.sendReportInput };
  departmentLists = { ...this.timeReportModels.departmentLists };

  /* Init Data Models for time-report module[End] */

  /*reset Send Report */
  resetSendReport(): void {
    this.timeReportModels.sendReportInput.tasks = [];
    this.timeReportModels.sendReportInput.events = [];
    this.sendReportInput = { ...this.timeReportModels.sendReportInput };
  }
}
