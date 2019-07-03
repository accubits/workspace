import { Injectable } from '@angular/core';

@Injectable()
export class TarDataService {

  constructor() { }
  tarModel = {

    wtInfo: {
      show: false
    },
    workReportun: {
      show: false
    },
    workReportex: {
      show: false
    },
    absenceFilter: {
      show: false
    },
    dailyReportun: {
      show: false
    },
    dailyReportc: {
      show: false
    },
    newAbsence: {
      show: false
    },
    absenceDetail: {
      show: false
    },
    worktimeFilter: {
      show: false
    },
    workreportFilter: {
      show: false
    },
    wtManagement: {
      show: false
    },
    wtHr: {
      show: false
    },
    wtSales: {
      show: false
    },
    wtSm: {
      show: false
    },
    wrManagement: {
      show: false
    },
    wrHr: {
      show: false
    },
    wrSales: {
      show: false
    },
    wrSm: {
      show: false
    },
    abMonth: {
      show: false
    },
    abWeek: {
      show: false
    },
    abDay: {
      show: false
    },
    wrYear: {
      show: false
    },
    wrMonth: {
      show: false
    },
    wrWeek: {
      show: false
    },
  };

  wtInfo = { ...this.tarModel.wtInfo };
  workReportun = { ...this.tarModel.workReportun };
  workReportex = { ...this.tarModel.workReportex };
  absenceFilter = { ...this.tarModel.absenceFilter };
  dailyReportun = { ...this.tarModel.dailyReportun };
  dailyReportc = { ...this.tarModel.dailyReportc };
  newAbsence = { ...this.tarModel.newAbsence };
  absenceDetail = { ...this.tarModel.absenceDetail };
  worktimeFilter = { ...this.tarModel.worktimeFilter };
  workreportFilter = { ...this.tarModel.workreportFilter };
  wtManagement = { ...this.tarModel.wtManagement };
  wtHr = { ...this.tarModel.wtHr };
  wtSales = { ...this.tarModel.wtSales };
  wtSm = { ...this.tarModel.wtSm };
  wrManagement = { ...this.tarModel.wrManagement };
  wrHr = { ...this.tarModel.wrHr };
  wrSales = { ...this.tarModel.wrSales };
  wrSm = { ...this.tarModel.wrSm };
  abMonth = { ...this.tarModel.abMonth };
  abWeek = { ...this.tarModel.abWeek };
  abDay = { ...this.tarModel.abDay };
  wrYear = { ...this.tarModel.wrYear };
  wrMonth = { ...this.tarModel.wrMonth };
  wrWeek = { ...this.tarModel.wrWeek };

}
