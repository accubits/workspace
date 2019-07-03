import { Injectable } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { HrmApiService } from '../shared/services/hrm-api.service';
import { HrmDataService } from '../shared/services/hrm-data.service';
import { ToastService } from '../shared/services/toast.service';
import { SettingsDataService } from '../shared/services/settings-data.service';
import * as _moment from 'moment';
import { UtilityService } from '../shared/services/utility.service';
import { CookieService } from 'ngx-cookie-service';
import { SettingsApiService } from '../shared/services/settings-api.service';

@Injectable()
export class HrmSandboxService {
  constructor(private spinner: Ng4LoadingSpinnerService,
    public hrmApiService: HrmApiService,
    public hrmDataService: HrmDataService,
    public settingsDataService: SettingsDataService,
    private toastService: ToastService,
    private utilityService: UtilityService,
    private settingsApiService: SettingsApiService,
    private cookieService: CookieService) { }

  /* Sandbox to handle API call for getting  all employees[Start] */
  getAllEmployee() {
    this.spinner.show();
    return this.hrmApiService.getAllEmployee().subscribe((result: any) => {
      this.hrmDataService.employeeList.list = result.data.employees;
      let existingUsers = this.hrmDataService.employeeList.list.filter(
        part => part.userSlug === this.cookieService.get('userSlug'))[0];
      if (existingUsers) {
        existingUsers.existing = true;
      }
      for (let i = 0; i < this.hrmDataService.employeeList.list.length; i++) {
        for (let j = 0; j < this.hrmDataService.employeeList.list[i].employeeDepartments.length; j++) {
          if (this.hrmDataService.employeeList.list[i].employeeDepartments[j].isHead === true) {
            this.hrmDataService.employeeList.list[i].isHead = true;
          }
          else {
            this.hrmDataService.employeeList.list[i].isHead = false;
          }
        }
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting  all employees[end] */

   /* Sandbox to handle API call for getting drive file list[Start] */
   getCountryList() {
    this.spinner.show();
    // Accessing drive API service
    return this.hrmApiService.getCountryList().subscribe((result: any) => {
      this.hrmDataService.getCountryList.countries = result.data.countries;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting drive file list[end] */


  /* Sandbox to handle API call getting leave type list[Start] */
  getLeaveTypeList() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.getLeaveTypeList().subscribe((result: any) => {
      this.hrmDataService.leaveType.leaveTypeList = result.data.leaveTypes;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call getting leave type list[end] */


  /* Sandbox to handle API call for getting  department list[Start] */
  getAllDepartment() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.getAllDepartment().subscribe((result: any) => {
      this.hrmDataService.departmentList.list = result.data.departments;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting  department list[End] */

  /* Sandbox to handle API call for getting  department list[Start] */
  getProfile() {
    this.spinner.show();
    // Accessing task API service
    return this.settingsApiService.fetchProfileDetailsEdit().subscribe((result: any) => {
      if (result.data.reportingManagerId !== null) {
        this.hrmDataService.reportingManagerDetails = result.data.reportingManagerDetails;
      }
      else {
        this.hrmDataService.reportingManagerDetails = null;
      }
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting  department list[End] */

  /* Sandbox to handle API call for creating leave type[Start] */
  createLeaveType() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.hrmApiService.createLeaveType().subscribe((result: any) => {
      this.hrmDataService.resetLeaveType();
      this.getLeaveTypeList();
      this.hrmDataService.newLeavePop.show = false;
      this.hrmDataService.leaveTypePop.show = false;
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for creating leave type[End] */

  /* Sandbox to handle API call for creating holiday[Start] */
  createHoliday() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.hrmApiService.createHoliday().subscribe((result: any) => {
      this.hrmDataService.addHoliday.show = false;
      this.hrmDataService.deletePopUp.show = false;
      this.hrmDataService.resetPopUp();
      this.getHolidayList();
      this.hrmDataService.resetHoliday();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for creating holiday[End] */

   /* Sandbox to handle API call for creating holiday[Start] */
   updateProfile() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.hrmApiService.updateProfile().subscribe((result: any) => {
      this.hrmDataService.editEmpDetail.show = false;

      this.getAllEmployee();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for creating holiday[End] */


  /* Sandbox to handle API call for getting holiday list[Start] */
  getHolidayList() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.getHolidayList().subscribe((result: any) => {
      this.hrmDataService.holiday.holidayList = result.data.holidays;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting holiday list[End] */

  /* Sandbox to handle API call for getting holiday list[Start] */
  getEmployeeleave() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.getEmployeeleave().subscribe((result: any) => {
      this.hrmDataService.leaveList.levelist = result.data.leaveInfo;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting holiday list[End] */

  /* Sandbox to handle API call for getting Abcent Chart list[Start] */
  getAbcentChartList() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.getAbcentChartList().subscribe((result: any) => {
      this.hrmDataService.absentChart.absentChartList = result.data.absenceChart;
      this.hrmDataService.absentChart.leaveReportList = result.data.absenceChart[0].users[0].leaveReport;

      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting Abcent Chart list[End] */

  /* Sandbox to handle API call for getting holiday list[Start] */
  getLeaveList() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.getLeaveList().subscribe((result: any) => {
      this.hrmDataService.leaveList.levelist = result.data.leaveRequests;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting holiday list[End] */

  setTrainingstatus(){
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.setTrainingStatus().subscribe((result: any) => {
      this.toastService.Success(result.data.msg);
      this.hrmDataService.getTrainingDetails.tab = 'myTrainings'
      this.getRequestList();
      this.spinner.hide();
    },
      err => {
        this.toastService.Error(err.msg);
        console.log(err);
        this.spinner.hide();
      })
  }

  /* Sandbox to handle API call for getting holiday list[Start] */
  getTrainingSettings() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.getTrainingSettings().subscribe((result: any) => {
      this.hrmDataService.trainingStatus = result.data.settings;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting holiday list[End] */
  

  /* Sandbox to handle API call for getting holiday list[Start] */
  setStartTraining() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.setStartTraining().subscribe((result: any) => {
      this.toastService.Success(result.data.msg);
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting holiday list[End] */

   /* Sandbox to handle API call for getting holiday list[Start] */
   completeTraining() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.completeTraining().subscribe((result: any) => {
      this.toastService.Success(result.data.msg);
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting holiday list[End] */

  /* Sandbox to handle API call for getting holiday list[Start] */
  setTrainingBudget() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.setTrainingBudget().subscribe((result: any) => {
      this.toastService.Success(result.data.msg);
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting holiday list[End] */


  /* Sandbox to handle API call for getting holiday list[Start] */
  setTrainingFeedback() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.setTrainingFeedback().subscribe((result: any) => {
      this.toastService.Success(result.data.msg);
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting holiday list[End] */


  /* Sandbox to handle API call for approve leave request[Start] */
  approveLeave() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.approveLeave().subscribe((result: any) => {
      this.toastService.Success(result.data.msg);
      this.hrmDataService.leavePop.show = false;
      this.hrmDataService.deletePopUp.show = false;
      this.getLeaveList();
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for approve leave request[End] */


  /*Code by Thuhin*/
  /* Fetch Company Tree[Start] */
  getCompanyTree() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.getCompanyTree().subscribe((result: any) => {
      this.hrmDataService.companyTree = result.data;
      this.spinner.hide();
    },
      err => {

        this.toastService.Error(err.msg);
      })
  }

  /* Get all Departments details[Start] */
  getDpmntDetails() {
    this.spinner.show();
    // Accessing hrm API service
    return this.hrmApiService.getDepartmentDetails().subscribe((result: any) => {
      this.hrmDataService.departmentDetails = result.data;
      this.spinner.hide();
    },
      err => {
        this.toastService.Error(err.msg);
      })
  }
  /* Get all Departments details[End] */



  /* Fetch Department Tree Structure[Start] */

  getDepartmentTree() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.getDepartmentTree().subscribe((result: any) => {
      if(result.data.departments.length !== 0){
        this.hrmDataService.orgTree = result.data.departments[0];
      }
      
      this.spinner.hide();
    },
      err => {

        this.toastService.Error(err.msg);
      })
  }
  /* Fetch Department Tree Structure[End] */

  /* add emloyees to department[Start] */
  addMembersToDeptmnt() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.setEmployee().subscribe((result: any) => {
      this.toastService.Success(result.data.msg);
      this.spinner.hide();
    },
      err => {
        this.toastService.Error(err.msg);
      })
  }

  /* add emloyees to department[Start] */

  /* Fetch Company Sub Tree[Start] */
  getCompanySubTree() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.getCompanySubTree().subscribe((result: any) => {
      this.hrmDataService.companySubTree = result.data;
      this.spinner.hide();
    },
      err => {

        this.toastService.Error(err.msg);
      })
  }


  /* Sandbox to handle API call for getting employee information[Start] */
  empInformation() {
    this.spinner.show();
    return this.hrmApiService.getEmployeeDetails().subscribe((result: any) => {
      this.hrmDataService.getEmployeeDetails = result.data;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting employee information[End] */


  /* delete employees to department[Start] */
  deleteMembersToDepartment() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.setEmployee().subscribe((result: any) => {
      this.toastService.Success(result.data.msg);
      this.spinner.hide();
    },
      err => {

        this.toastService.Error(err.msg);
      })
  }

  /* delete employees to department[Start] */





  /* Get all Departments of company[Start] */
  getDepartments() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.getDepartments().subscribe((result: any) => {
      this.hrmDataService.allDepartmentsList = result.data;
      // console.log('parentlist', this.hrmDataService.allDepartmentsList)
      this.spinner.hide();
    },
      err => {
        this.toastService.Error(err.msg);
      })
  }
  /* Get all Departments of company[End] */

  /* create new departments for a company[Start] */
  createNewDept() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.createNewDept().subscribe((result: any) => {
      this.toastService.Success(result.data.msg);
      this.getDepartmentTree();
      this.hrmDataService.deptPop.show = false;
      this.spinner.hide();
    },
      err => {

        this.toastService.Error(err);
        this.spinner.hide();
      })
  }
  /* create new departments for a company[Start] */

  /* Delete departments for a company[Start] */

  deleteDepartment() {
    this.spinner.show();
    return this.hrmApiService.deleteDept().subscribe((result: any) => {
      this.toastService.Success(result.data);
      this.getDepartmentTree();
      this.hrmDataService.deleteTreeDepartment.show = false;
      this.getAllDepartment();
    
      this.spinner.hide();
    },
      err => {

        this.toastService.Error(err);
        this.spinner.hide();
      })
  }

  /* Delete departments for a company[End] */



  /* create new departments for a company[Start] */
  updateNewDept() {
    this.spinner.show();
    // Accessing task API service
    return this.hrmApiService.editDept().subscribe((result: any) => {
      this.toastService.Success(result.data.msg);
      this.hrmDataService.deptPop.show = false;
      this.getDepartmentTree();
      this.hrmDataService.showEditDepartment.show = false;
      //this.getDepartments();
      this.spinner.hide();
    },
      err => {

        this.toastService.Error(err);
        this.spinner.hide();
      })
  }
  /* create new departments for a company[Start] */
  /* Sandbox to handle API call for create absence[Start] */
  createAbsence() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.hrmApiService.createAbsence().subscribe((result: any) => {
      this.hrmDataService.newAbsence.show = false;
      this.getAbcentChartList();
      this.hrmDataService.deletePopUp.show = false;
      this.hrmDataService.absenceDetail.show = false;
      this.hrmDataService.resetAbsense()
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for create absence[end] */

  /* Sandbox to handle API call for crate leave request[Start] */
  createLeavReq() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.hrmApiService.createLeave().subscribe((result: any) => {
      this.hrmDataService.requestPop.show = false;
      this.hrmDataService.reqConformPopup.show = false;
      this.hrmDataService.deletePopUp.show = false;
      this.hrmDataService.leavePop.show = false;
      this.getLeaveList();
      this.hrmDataService.resetLeave();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for crate leave request[end] */

  /* Sandbox to handle API call for invite employee[Start] */
  inviteEmployee() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.hrmApiService.inviteEmployee().subscribe((result: any) => {
      this.hrmDataService.invitePop.show = false;
      this.getAllEmployee();
      this.spinner.hide();
      this.toastService.Success(result.data);
    },
      err => {
        console.log(err.msg);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for invite employee[End] */

  /* Sandbox to handle API call for register employee[Start] */
  registerEmployee() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.hrmApiService.registerEmployee().subscribe((result: any) => {
      this.hrmDataService.invitePop.show = false;
      this.getAllEmployee();
      this.spinner.hide();
      this.toastService.Success(result.data);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for register employee[End] */

  /* Sandbox to handle API call for training request list[Start] */
  getRequestList() {
    this.spinner.show();
    // Accessing hrm API service
    return this.hrmApiService.getTrainingRequestList().subscribe((result: any) => {
      for (let i = 0; i < result.data.trainings.length; i++) {
        var start = _moment(this.utilityService.convertTolocale(result.data.trainings[i].startsOn)); //todays date
        var end = _moment(this.utilityService.convertTolocale(result.data.trainings[i].endsOn)); // another date
        var duration = _moment.duration(end.diff(start));
        var days = duration.asDays();
        result.data.trainings[i].duration = days;
      }
      this.hrmDataService.requestTable.list = result.data.trainings;
      this.hrmDataService.myTrainingTable.list = result.data.trainings;

      this.hrmDataService.showStatus.requestCount = this.hrmDataService.requestTable.list.filter(
        approvedStatus => (approvedStatus.status === 'awaitingApproval'  && approvedStatus.isCancelled === true) ||
        (approvedStatus.status === 'awaitingApproval'  && approvedStatus.isCancelled === false))
   
      this.hrmDataService.showStatus.approvedCount = this.hrmDataService.requestTable.list.filter(
        approvedStatus => approvedStatus.status === 'approved' &&  approvedStatus.isOnGoing === false)

      this.hrmDataService.showStatus.ongoingCount = this.hrmDataService.requestTable.list.filter(
        ongoingStatus => ongoingStatus.isOnGoing === true && ongoingStatus.status === 'approved' && ongoingStatus.isCompleted === false)
        
        this.hrmDataService.showStatus.completeCount = this.hrmDataService.requestTable.list.filter(
          approvedStatus => approvedStatus.isCompleted === true)
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for training request list[End] */



  /* Sandbox to handle API call for delete employee[start] */
  deleteEmployee() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.hrmApiService.deleteEmployee().subscribe((result: any) => {
      this.hrmDataService.deletePopUp.show = false;
      this.hrmDataService.resetPopUp();
      this.getAllEmployee();
      this.spinner.hide();
      this.toastService.Success(result.data);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err);
      })
  }
  /* Sandbox to handle API call for delete employee[end] */

  /* Sandbox to handle API call for update employee[start] */
  updateEmployee() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.hrmApiService.updateEmployee().subscribe((result: any) => {
      this.hrmDataService.invitePop.show = false;
      this.getAllEmployee();
      this.hrmDataService.resetEmployee();
      this.hrmDataService.resetPopUp();
      this.spinner.hide();
      this.toastService.Success(result.data);
    })
  }
  /* Sandbox to handle API call for update employee[end] */

  /* Sandbox to handle API call for Creating training requets[Start] */
  createTrainingRequests() {
    this.spinner.show();
    // Accessing hrm API service
    return this.hrmApiService.createTrainingRequest().subscribe((result: any) => {
      this.hrmDataService.trainingRequestForm.show = false;
      this.hrmDataService.deleteTrainingRequestForm.show = false;
      this.hrmDataService.resetTrainingRequest();
      this.hrmDataService.trainingDetail.show = false;
      this.hrmDataService.trainingRequest.show = false;
      this.hrmDataService.toUsers.toUsers = [];

      console.log('tab', this.hrmDataService.getTrainingDetails.tab);
      this.getRequestList();

      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for Creating training requets[End] */

  /* Sandbox to handle API call for approve training requets[Start] */
  setTrainingRequestStatus() {
    this.spinner.show();
    // Accessing hrm API service
    return this.hrmApiService.setTrainingRequestStatus().subscribe((result: any) => {
        this.hrmDataService.getTrainingDetails.tab = 'request'
        this.getRequestList();
      this.hrmDataService.deleteConfirmPopup.show = false;
      this.hrmDataService.ongoingTrainingDetail.show = false;
      this.hrmDataService.trainingRequest.show = false;
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for approve training requets[End] */

  /* Sandbox to handle API call for Creating performance[Start] */
  addPerformance() {
    this.spinner.show();
    // Accessing hrm API service
    return this.hrmApiService.createPerformance().subscribe((result: any) => {
      this.hrmDataService.newModule.show = false;
      this.hrmDataService.kraModuleDetail.show = false;
      this.hrmDataService.showEditPopup.show = false;


      this.hrmDataService.createPerformance.questions.splice(1, this.hrmDataService.createPerformance.questions.length - 1)
      for (let i = 0; i < this.hrmDataService.createPerformance.questions.length; i++) {
        this.hrmDataService.createPerformance.questions[i].question = '';
        this.hrmDataService.createPerformance.questions[i].type = 'answerText'
      }
      this.hrmDataService.resetPerformance();


      this.getAllPerformance();
      this.spinner.hide();
      this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for Creating performance[End] */

  /* Sandbox to handle API call for fetch all performances [Start] */
  getAllPerformance() {
    this.spinner.show();
    // Accessing activitystream API service
    return this.hrmApiService.getPerformaceDetails().subscribe((result: any) => {
      this.hrmDataService.getPerformance.kraModules = result.data.kraModules;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
        this.toastService.Error(err.msg);
      })
  }
  /* Sandbox to handle API call for fetch all performances[End] */


  /* Sandbox to handle API call for getting All Form[Start] */
  getAllForms() {
    this.spinner.show();
    // Accessing forms API service
    return this.hrmApiService.getAllForms().subscribe((result: any) => {
      this.hrmDataService.getAllForms = result.data;
      // this.hrmDataService.getCourseForms = result.data;

      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting all Forms[End] */


  /* Sandbox to handle API call for getting All Form[Start] */
  getCourseForm() {
    this.spinner.show();
    // Accessing forms API service
    return this.hrmApiService.getAllForms().subscribe((result: any) => {
      this.hrmDataService.getCourseForms = result.data.forms;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting all Forms[End] */

  /* Sandbox to handle API call for getting All Form[Start] */
  getFeedbackForm() {
    this.spinner.show();
    // Accessing forms API service
    return this.hrmApiService.getAllForms().subscribe((result: any) => {
      this.hrmDataService.getFeedbackForms = result.data.forms;

      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();

      })
  }
  /* Sandbox to handle API call for getting all Forms[End] */


  /*FORM REUSE--------------------------------------*/

  /* Sandbox to handle API call for Creating Form Partially[Start] */
  createNewFormPartial() {
    this.spinner.show();
    this.hrmDataService.hrmformAPICall.inProgress = true;
    // Accessing forms API service

    return this.hrmApiService.createNewFormPartial().share();
  }
  /* Sandbox to handle API call for Creating Form[End] */

}
