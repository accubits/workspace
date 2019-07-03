import { Injectable } from '@angular/core';

@Injectable()
export class CalendarDataService {

  eventView : boolean = false;  
  constructor() { }

  calendarReportModel={
    showEventDetails:{
      show : false
    },
    calendarModels:{
      eventModel : {
        showPopup : false
      }
    },
    createEvent:{
      createPopup : {
        showPopup : false
      }
    },
    calenderReport: {
      calendarDetails: {
        calender:[],
        // date: '',
        // dayName: '',
        // event:[],
        // task: [],
        // totalCount: '',
      },
      calendar:[{
        date: '',
        dateStr: '',
        dayName: '',
        overview:{
          event:[],
          task:[]
        },
        timing:[{
          taskAndEvents:{
            event:[],
            task:[]
          }
        }]
      }]
    },
    getCalendarInput: {
      day:null,
      month:null,
      year:'',
      type:'',
      timezone:''
    },
  }
  eventModel = {...this.calendarReportModel.calendarModels.eventModel};
  createPopup = {...this.calendarReportModel.createEvent.createPopup};
  calenderReport = {...this.calendarReportModel.calenderReport};
  getCalendarInput = {...this.calendarReportModel.getCalendarInput};
  showEventDetails = {...this.calendarReportModel.showEventDetails}
}
