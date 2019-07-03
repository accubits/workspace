
import { MyPerformanceComponent } from './../../hrm/my-performance/my-performance.component';
import { ReviewHistoryComponent } from './../../hrm/review-history/review-history.component';
import { KraModuleDetailComponent } from './../../hrm/kra-module-detail/kra-module-detail.component';
import { HrmModule } from './../../hrm/hrm.module';

import {
  Injectable
} from '@angular/core';
import {
  CookieService
} from 'ngx-cookie-service';

@Injectable()
export class HrmDataService {

  constructor(private cookieService: CookieService) { }
  hrmModels: any = {
    addAppraisal: {
      show: false
    },
    trainingRequestSlug:'',

    appraisalReview: {
      show: false
    },

    leaveType: {
      sortOption: 'name',
      sortMethod: 'asc',
      action: 'create',
      leaveTypeSlug: null,
      page: 1,
      name: '',
      resonType: 'Paid',
      type: 'paid',
      colorCode: '#ffcaca',
      description: '',
      isApplicableFor: true,
      leaveCount: '1',
      leaveTypeList: [],
    },

    companyTree: {
      "departments": {
        "departmentSlug": "",
        "departmentName": "",
        "departmentHeadName": "",
        "departmentHeadImageUrl": "",
        "departmentHeadEmail": "",
        "departmentHeadUserSlug": "",
        "child": []
      }
    },

    companySubTree: {
      departmentSlug: "",
    },

    orgTree:
    {
     
    },

    allDepartmentsList: {
      "current_page": null,
      "from": null,
      "last_page": null,
      "per_page": null,
      "to": null,
      "total": null,
      "departments": [{
        "departmentSlug": "",
        "departmentName": "",
        "orgSlug": "",
        "parentDepartmentSlug": null,
        "rootDepartmentSlug": null
      },

      ]
    },

    departmentDetailsSlug: {
      departmentSlug: ""
    },

    setEmployeeDetails: {
      action: "",
      departmentSlug: "",
      employeeSlug: "",
      isHead: false
    },

    departmentDetails: {
      current_page: null,
      from: null,
      last_page: null,
      per_page: null,
      to: null,
      total: null,
      departmentDetails: {
        departmentSlug: "",
        departmentName: "",
        orgSlug: "",
        departmentHeadName: null,
        departmentHeadImageUrl: null,
        departmentHeadUserSlug: null,
        departmentHeadEmail: null,
        rootDepartmentSlug: null,
        parentDepartmentSlug: null,
        parentDepartmentName: null,
        parentDepartmentHeadName: null,
        parentDepartmentHeadImageUrl: null,
        parentDepartmentHeadUserSlug: null,
        parentDepartmentHeadEmail: null,
        childDepartments: []
      },
      members: []
    },

    getCountryList: {
      countries : []
    },

    createDept: {
      orgSlug: "",
      parentDepartmentSlug: null,
      rootDepartmentSlug: null,
      name: "",
      departmentSlug: '',
      employeeSlug: ""
    },
    deptMainName:{
      paretDeptName: "",
    },

    editDept: {
      orgSlug: "",
      name:'',
      parentDepartmentSlug: '',
      rootDepartmentSlug: '',
      paretDeptName: "",
      departmentHeadName: '',
      employeeSlug: "",
      departmentSlug: ''
    },
    editDeptSlug:{
      slug: null,
    },
    deleteDepartment:{
      departmentSlug:'',
    },

    selectedData: {
      leaveTypeSlug: '',
      name: '',
      colorCode: '',
      description: '',
      maximumLeave: '',
      type: '',
      users: [],
      isApplicableFor: true,
      allEmployees: false
    },

    holiday: {
      sortOption: 'name',
      sortMethod: 'asc',
      action: 'create',
      page: 1,
      holidayList: [],
      holidaySlug: null,
      name: '',
      holidayDate: null,
      isRestricted: 0,
      info: '',
      repeted: 0,
    },

    absent: {
      action: 'create',
      absentSlug: null,
      absentUser: null,
      absentStartsOn: null,
      startsOnHalfDay: false,
      absentEndsOn: null,
      endsOnHalfDay: false,
      isHalfDay: false,
      absentTypeSlug: null,
      type: [],
      reason: '',
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
      leaveType: '',
      reason: '',
      leaveTypeColorCode: ''
    },

    absentChart: {
      monthYear: '',
      month: '',
      year: '',
      filter: {
        departmentSlug: '',
        departmentName: '',
        leaveTypeSlugs: []
      },
      absentChartList: [],
      leaveReportList: [],
      userSlug: null,
      userName: '',
      userImage: null,
      leaveReport: {
        day: '',
        leaveFrom: null,
        leaveTo: null,
        leaveFromDateStr: null,
        leaveToDateStr: null,
        leaveFromDayStr: null,
        leaveToDayStr: null,
        leaveFromIsHalfDay: false,
        leaveToIsHalfDay: false,
        leaveType: null,
        leaveTypeColorCode: '',
        leaveReason: ''
      }
    },

    leaveList: {
      year: '',
      sortOption: 'dateFrom',
      sortMethod: 'asc',
      tab: '',
      levelist: [],
    },

    leaveCreate: {
      action: 'create',
      leaveSlug: null,
      requestUser: null,
      leaveStartsOn: '',
      startsOnHalfDay: false,
      leaveEndsOn: '',
      endsOnHalfDay: false,
      leaveTypeSlug: null,
      type: [],
      reason: '',

    },

    reportingManagerDetails: {
      reportingManagerSlug: '',
      reportingManagerName: '',
      reportingManagerImgUrl: ''
    },

    requestDetails: {
      leaveSlug: null,
      dateFrom: null,
      startsOnHalfDay: false,
      dateTo: null,
      endsOnHalfDay: false,
      userImage: '',
      userName: '',
      requestToImage: '',
      requestToName: '',
      leaveType: '',
      reason: '',
      colorCode: '',
      isApprove: false
    },

    employee: {
      option: 'invite',
      sortOption: 'employeeName',
      sortMethod: 'asc',
      action: 'create',
      empSlug: '',
      name: '',
      email: '',
      password: '',
      conPassword: '',
      phoneNo: '',
      roleSlug: null,
      departmentSlug: null,
      reportManager: '',
      reportManagerEmpSlug: null
    },

    getEmployeeDetails: {
      userSlug: null,
      name: '',
      email: '',
      userStatus: false,
      firstName: '',
      lastName: '',
      birthDate: '',
      userImage: '',
      phone: '',
      imageUrl: '',
      joiningDate: '',
      streetAddress: '',
      addressLine2: '',
      city: '',
      state: '',
      zipcode: '',
      country: '',
      interest: [
        {
          title: '',
          slug: ''
        },
      ],
      reportingManagerDetails: {
        reportingManagerSlug: '',
        reportingManagerName: '',
        reportingManagerImgUrl: null
      },

      departments: [
        {
          departmentName: ''
        }
      ],

    },

    optionBtn: {
      show: false
    },

    userList: {
      show: false
    },

    selctType: {
      show: false
    },

    leavePop: {
      show: false
    },

    deptPop: {
      show: false
    },

    deptDetails: {
      show: false
    },

    editDeptPop: {
      show: false
    },
    hideField: {
      show: false
    },

    employeeList: {
      list: [],
      searchEmpTxt: '',
    },

    departmentList: {
      list: [],
      toDept: [],
      slug: [],
    },

    requestPop: {
      show: false
    },

    leaveTypePop: {
      show: false
    },

    newLeavePop: {
      show: false
    },

    invitePop: {
      show: false
    },

    departmentCount: {
      show: false
    },

    toUsers: {
      toAllEmployee: true,
      toUsers: [],
      slug: [],
    },


    addHoliday: {
      show: false
    },
    departmentUpdateOptions: {
      show: false
    },

    absenceDetail: {
      show: false
    },

    showLevDetail: {
      show: false
    },

    absenceFilter: {
      show: false
    },

    newAbsence: {
      show: false
    },

    deletePopUp: {
      show: false
    },

    reqConformPopup: {
      show: false
    },

    deleteMessage: {
      msg: ''
    },

    userCount: {
      show: false
    },

    empDetail: {
      show: false
    },

    editEmpDetail: {
      show: false
    },

    selectedOption: {
      option: ''
    },

    trainingDetail: {
      show: false
    },

    trainingRequest: {
      show: false
    },

    ongoingTraining: {
      show: false
    },

    ongoingTrainingDetail: {
      show: false
    },

    completedTrainingDetail: {
      show: false
    },

    trainingRequestForm: {
      show: false
    },

    activeTrainingDetail: {
      show: false
    },
    
    requestTable: {
      list: [],
    },


    myTrainingTable: {
      list: [],
    },

    employeeDetails: {
      selectedAll: false,
      selectedEmployeeIds: [],
      showPopup: false
    },

    deleteTrainingRequestForm: {
      show: false
    },

    deleteTreeDepartment: {
      show: false
    },

    createTrainingRequest: {
      action: 'create',
      name: '',
      details: '',
      cost: '',
      slug: '',
      approverEmployeeSlug: '',
      employeeName: '',
      startsOn: null,
      endsOn: null,
    },

    setTraining: {
      slug: null,
      status: 'start'
    },

    approveTrainingRequest: {
      slug: '',
      status: '',
      hasFeedbackForm: true,
      forms: {
        postTrainingFormSlug: null,
        postCourseFormSlug: null
      }
    },

    getPerformance: {
      count: 0,
      kraModules: []
    },

    getTrainingDetails: {
      tab: ''
    },

    showFeedbackForm: {
      show: false
    },
    newModule: {
      show: false
    },
    kraModuleDetail: {
      show: false
    },
    selectedElement: {
      isValidated: true,
    },

    showEditPopup: {
      show: false
    },

    createPerformance: {
      action: 'create',
      title: '',
      description: '',
      kraModuleSlug: '',
      questions: [{
        type: 'answerText',
        action: 'create',
        questionSlug: null,
        enableComment: false,
        question: ''
      }]
    },

    checkQuestionType: {
      checkType: ''
    },

    moreOption: {},

    selectPopup: {
      show: false
    },
    reviewDetailPop: {
      show: false
    },
    activeTrainingPop: {
      show: false
    },
    newFormCreation: {
      show: false
    },
    finishedPop: {
      show: false
    },

    setTrainingStatus: {
       slug: null,
       status : ''
    },

    setTrainingRequestStatus: {
      slug: null,
      status: '',
      hasFeedbackForm: true,
      forms: {
        postTrainingFormSlug: null,
        postCourseFormSlug: null
      }
    },

    showStatus: {
      requestCount: [],
      approvedCount: [],
      ongoingCount: []
    },

    deleteConfirmPopup: {
      show: false
    },
    selectedRequest: {},
    selectedPerformance: {},
    selectedApprovedRequest: {},
    showEditDepartment: {
      show: false
    },
    showAddChild:{
      show: false
    },

    annualReviewDetail: {
      show: false
    },
    getAllForms: {
      forms: [],
      page: 1,
      perPage: 50,
      type: '',
      searchTxt: '',
      sortOrder: 'desc',
      sortBy: 'createdAt',
      tab: 'active'
    },

    getCourseForms: {
      forms: [],
      page: 1,
      perPage: 50,
      type: '',
      searchTxt: '',
      sortOrder: 'desc',
      sortBy: 'createdAt',
      tab: 'active'
    },

    getFeedbackForms: {
      forms: [],
      page: 1,
      perPage: 50,
      type: '',
      searchTxt: '',
      sortOrder: 'desc',
      sortBy: 'createdAt',
      tab: 'active'
    },

    selectOverviewDetails: {
      approvedAt: null,
      approverEmployeeSlug: null,
      approverUserImageUrl: '',
      approverUserName: '',
      approverUserSlug: null,
      cost: '',
      details: '',
      employeeSlug: null,
      endsOn: null,
      imageUrl: '',
      inProgress: false,
      isCancelled: false,
      isCompleted: false,
      isOnGoing: false,
      name: '',
      postCourseFormSlug: null,
      postCourseFormTitle: '',
      postTrainingFormSlug: null,
      postTrainingFormTitle: '',
      requestedOn: null,
      slug: null,
      startsOn: null,
      status: '',
      userName: '',
      userSlug: null,
    },
    trainingStatus: {
      trainingBudget: {
        currentBalance: null,
        totalBalance: null
      },
      trainingFeedback: {
        days: null
      },

      showScorePopup:{
        show:false,
        score:''
      }


    },


    /*FORM REUSE[START]*/
    createHrmForm: {
      title: "",
      description: "",
      action: 'create',
      formAccessType: "internal",
      formStatus: "",
      isTemplate: false,
      isArchived: false,
      isPublished: false,
      allowMultiSubmit: false,
      formSlug: '',
      formComponents: []
    },

    createHrmFormPartial: {
      title: "",
      description: "",
      action: 'create',
      formAccessType: "internal",
      formStatus: "",
      isTemplate: false,
      isArchived: false,
      isPublished: false,
      allowMultiSubmit: false,
      formSlug: '',
    },

    hrmformAPICall: {
      inProgress: false
    },

    hrmformValidation: {
      validating: false
    },

    viewhrmForm: {
      selctedFormSlug: '',
      selctedAnsSlug: '',
      permission: 'view',
      selectedFormDetails: null,
      pageSlugs: [],
      selectdPageSlug: '',
      selectedPage: {},
      showSubmit: false,
      formResponse: {
        formComponents: []
      },
      updatedStatus: '',
      unPublishmodal: {
        show: false
      },
      previewOnlyElements: [],
      previewOnlyShow: false,
    },

    hrmformElementToggle: {
      activeElement: false,
      activeIndex: ''
    },

    createAppraisalCycle: {
      action: '',
      orgSlug: null,
      appraisalCycleSlug: null,
      title: '',
      description: '',
      cycle: {
        type: '',
        startDate: null,
        endDate: null,
        processingStartDate: null,
        processingEndDate: null
      },
      applicable: {
        type: '',
        departments: [],
        employees: []
      },
      reviewers: {
        includeDepartmentHead: true,
        departments: [],
        includeEmployee: true,
        employees: []
      },
      mainModules: [
        {
          mainModule: 'timeAndReport',
          weightagePercent: 0
        },
        {
          mainModule: 'taskScore',
          weightagePercent: 0
        },
        {
          mainModule: 'performanceIndicator',
          weightagePercent: 0
        }
      ],
      performanceIndicator: [
        {
          kraModuleSlug: null,
          weightagePercent: 0
        },
        {
          kraModuleSlug: null,
          weightagePercent: 0
        }
      ]
    },
  };

  newHrmForm = {
    hrmnew_form: {
      show: false
    },
    fbl_fixed: {
      show: false
    },
  };

  hrmpublishForm = {
    hrmpublish_form: {
      show: false
    },
    hrmformAdminmodal: {
      show: false
    },


  };

  /*FORM REUSE[END]*/

  showScorePopup ={ ...this.hrmModels.showScorePopup};
  editDeptSlug = { ...this.hrmModels.editDeptSlug};
  createAppraisalCycle = { ...this.hrmModels.createAppraisalCycle};
  getCourseForms = {...this.hrmModels.getCourseForms};
  getFeedbackForms = {...this.hrmModels.getFeedbackForms};
  appraisalReview = {...this.hrmModels.appraisalReview};
  getAllForms = { ...this.hrmModels.getAllForms};
  showEditDepartment = {...this.hrmModels.showEditDepartment};
  showAddChild ={...this.hrmModels.showAddChild};
  addAppraisal = { ...this.hrmModels.addAppraisal};
  selectedApprovedRequest ={ ...this.hrmModels.selectedApprovedRequest};
  showStatus ={ ...this.hrmModels.showStatus};
  deleteConfirmPopup = {...this.hrmModels.deleteConfirmPopup};
  selectPopup = { ...this.hrmModels.selectPopup};
  checkQuestionType = { ...this.hrmModels.checkQuestionType};
  setTrainingRequestStatus = { ...this.hrmModels.setTrainingRequestStatus }
moreOption = { ...this.hrmModels.moreOption};
getCountryList = { ...this.hrmModels.getCountryList};
selctType = { ...this.hrmModels.selctType};
selectedOption = { ...this.hrmModels.selectedOption};
getPerformance = {...this.hrmModels.getPerformance};
editDeptPop = { ...this.hrmModels.editDeptPop};
hideField = {...this.hrmModels.hideField};
deptDetails = { ...this.hrmModels.deptDetails};
setTraining = {...this.hrmModels.setTraining};
createTrainingRequest = { ...this.hrmModels.createTrainingRequest };
approveTrainingRequest = { ...this.hrmModels.approveTrainingRequest };
createPerformance = { ...this.hrmModels.createPerformance };
showEditPopup ={...this.hrmModels.showEditPopup};
selectedElement  = { ...this.hrmModels.selectedElement };
getTrainingDetails = { ...this.hrmModels.getTrainingDetails };
selectedRequest = { ...this.hrmModels.selectedRequest };
selectedPerformance = {...this.hrmModels.selectedPerformance};
showFeedbackForm = { ...this.hrmModels.showFeedbackForm };
requestDetails = { ...this.hrmModels.requestDetails };
employeeDetails = { ...this.hrmModels.employeeDetails };
absentChart = { ...this.hrmModels.absentChart };
leaveType = { ...this.hrmModels.leaveType };
holiday = { ...this.hrmModels.holiday };
absent = { ...this.hrmModels.absent };
absentDetails = { ...this.hrmModels.absentDetails };
leaveCreate = { ...this.hrmModels.leaveCreate };
leaveList = { ...this.hrmModels.leaveList };
employee = { ...this.hrmModels.employee };
invitePop = { ...this.hrmModels.invitePop };
selectedData = { ...this.hrmModels.selectedData };
leavePop = { ...this.hrmModels.leavePop };
requestPop = { ...this.hrmModels.requestPop };
leaveTypePop = { ...this.hrmModels.leaveTypePop };
newLeavePop = { ...this.hrmModels.newLeavePop };
toUsers = { ...this.hrmModels.toUsers };
employeeList = { ...this.hrmModels.employeeList };
getEmployeeDetails = { ...this.hrmModels.getEmployeeDetails };
departmentList = { ...this.hrmModels.departmentList };
deptPop = { ...this.hrmModels.deptPop };
addHoliday = { ...this.hrmModels.addHoliday };
absenceDetail = { ...this.hrmModels.absenceDetail };
absenceFilter = { ...this.hrmModels.absenceFilter };
showLevDetail = { ...this.hrmModels.showLevDetail };
newAbsence = { ...this.hrmModels.newAbsence };
deletePopUp = { ...this.hrmModels.deletePopUp };
reqConformPopup = { ...this.hrmModels.reqConformPopup };
optionBtn = { ...this.hrmModels.optionBtn };
setTrainingStatus = { ...this.hrmModels.setTrainingStatus };
userList = { ...this.hrmModels.userList };
deleteMessage = { ...this.hrmModels.deleteMessage };
userCount = { ...this.hrmModels.userCount };
empDetail = { ...this.hrmModels.empDetail };
editEmpDetail = { ...this.hrmModels.editEmpDetail };
companyTree = { ...this.hrmModels.companyTree };
companySubTree = { ...this.hrmModels.companySubTree };
allDepartmentsList = { ...this.hrmModels.allDepartmentsList };
createDept = { ...this.hrmModels.createDept };
deptMainName = {...this.hrmModels.deptMainName};
editDept = { ...this.hrmModels.editDept };
trainingDetail = { ...this.hrmModels.trainingDetail };
trainingRequest = { ...this.hrmModels.trainingRequest };
ongoingTraining = { ...this.hrmModels.ongoingTraining };
ongoingTrainingDetail = { ...this.hrmModels.ongoingTrainingDetail };
completedTrainingDetail = { ...this.hrmModels.completedTrainingDetail };
trainingRequestForm = { ...this.hrmModels.trainingRequestForm };
requestTable = { ...this.hrmModels.requestTable };
myTrainingTable = {...this.hrmModels.myTrainingTable};
activeTrainingPop = { ...this.hrmModels.activeTrainingPop};
deleteTrainingRequestForm = { ...this.hrmModels.deleteTrainingRequestForm };
deleteTreeDepartment = { ...this.hrmModels.deleteTreeDepartment};
departmentCount = { ...this.hrmModels.departmentCount };
newModule = {...this.hrmModels.newModule};
kraModuleDetail = {...this.hrmModels.KraModuleDetail};
departmentDetails ={...this.hrmModels.departmentDetails};
departmentDetailsSlug ={...this.hrmModels.departmentDetailsSlug};
setEmployeeDetails ={...this.hrmModels.setEmployeeDetails};
reviewDetailPop = { ...this.hrmModels.reviewDetailPop};
ReviewHistoryPop = { ...this.hrmModels.ReviewHistoryPop};
myPerformanceDetail = { ...this.hrmModels.myPerformanceDetail};
orgTree = {...this.hrmModels.orgTree};
departmentUpdateOptions = {...this.hrmModels.departmentUpdateOptions};
reportingManagerDetails = {...this.hrmModels.reportingManagerDetails};
deleteDepartment ={...this.hrmModels.deleteDepartment};
trainingStatus = {...this.hrmModels.trainingStatus};
annualReviewDetail = { ...this.hrmModels.annualReviewDetail};
activeTrainingDetail =  { ...this.hrmModels.activeTrainingDetail };
finishedPop = { ...this.hrmModels.finishedPop };
newFormCreation = {...this.hrmModels.newFormCreation};
selectOverviewDetails = {...this.hrmModels.selectOverviewDetails};
/*FORM REUSE[START]*/
createHrmForm ={...this.hrmModels.createHrmForm};
createHrmFormPartial={...this.hrmModels.createHrmFormPartial};
hrmnew_form = { ...this.newHrmForm. hrmnew_form};
hrmformAPICall = {...this.hrmModels.hrmformAPICall}
hrmformAdminmodal = { ...this.hrmpublishForm.hrmformAdminmodal };
hrmformValidation  = { ...this.hrmModels.hrmformValidation };
formElementArray =  [];
viewhrmForm:any = { ...this.hrmModels.viewhrmForm };
hrmformElementToggle = { ... this.hrmModels.hrmformElementToggle };
hrmpublish_form = { ...this.hrmpublishForm.hrmpublish_form };
trainingRequestSlug = {...this.hrmModels.trainingRequestSlug};




 /*reset form after create or edit */
 hrmresetForm(): void {
  this. createHrmForm = { ...this.hrmModels.createHrmForm};
  this.createHrmForm.formComponents = [];
  this.formElementArray = [];
  this.hrmformValidation  = { ...this.hrmModels. hrmformValidation };

  }
  /*FORM REUSE[END]*/

  resetLeaveType(): void {
    this.toUsers = { ...this.hrmModels.toUsers };
    this.leaveType = { ...this.hrmModels.leaveType };
    this.userCount = { ...this.hrmModels.userCount };
  }

  resetEditDept(): void {
    this.editDept = {...this.hrmModels.editDept}
  }

  resetreportingManagerDetails(): void {
    this.reportingManagerDetails = { ...this.hrmModels.reportingManagerDetails };
  }

  resetAbsense(): void {
    this.absent = { ...this.hrmModels.absent };
    this.toUsers = { ...this.hrmModels.toUsers };
  }

  resetTrainingRequest(): void {
    this.createTrainingRequest = { ...this.hrmModels.createTrainingRequest };
  }

  resetPerformance(): void {
    this.createPerformance = { ...this.hrmModels.createPerformance };
  }

  resetHoliday(): void {
    this.holiday = { ...this.hrmModels.holiday };
  }

  resetEmployee(): void {
    this.employee = { ...this.hrmModels.employee };
    this.departmentList.toDept = [];
  }

  resetLeave(): void {
    this.leaveCreate = { ...this.hrmModels.leaveCreate };
    this.toUsers = { ...this.hrmModels.toUsers };
  }

  resetPopUp(): void {
    this.optionBtn = { ...this.hrmModels.optionBtn };
    this.departmentCount = { ...this.hrmModels.departmentCount };
  }
  resetCreateDept(): void{
    this.createDept = {...this.hrmModels.createDept}
  }
}