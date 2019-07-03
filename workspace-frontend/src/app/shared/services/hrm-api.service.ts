import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Observable } from "rxjs/Observable";
import { Configs } from '../../config';
import { CookieService } from 'ngx-cookie-service';
import { UtilityService } from './utility.service';
import { HrmDataService } from '../../shared/services/hrm-data.service';

 @Injectable()
export class HrmApiService {
  constructor(private cookieService: CookieService,
    private hrmDataService: HrmDataService,
    private utilityService: UtilityService,
    private http: HttpClient) { }

  /* get leave type list [Start]*/
  getLeaveTypeList(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/fetchAllLeaveTypes'
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "page": this.hrmDataService.leaveType.page,
      "sortBy": this.hrmDataService.leaveType.sortOption,
      "sortOrder": this.hrmDataService.leaveType.sortMethod,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* get leave type list [End] */

  /* get employee list[Start] */
  getAllEmployee(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'orgmanagement/listEmployees'
    let data = {
      'orgSlug': this.cookieService.get('orgSlug'),
      'q': this.hrmDataService.employeeList.searchEmpTxt,
      'sortBy': this.hrmDataService.employee.sortOption,
      'sortOrder': this.hrmDataService.employee.sortMethod,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* get employee list[End] */

   /* API call for get drive files[Start] */
   getCountryList(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'common/country';
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.get(URL,  headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.json().error || 'Server error.'));
  }
  /* API call for get drive files[end] */

   /* get employee details[Start] */
   getEmployeeDetails(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'orgmanagement/fetchEmployeeInfo'
    let data = {
      "employeeSlug": this.hrmDataService.employee.empSlug
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* get employee list[End] */


/* set employee to department[Start] */

  setEmployee():Observable<any>{
    let URL = Configs.api + 'orgmanagement/setEmployeeToDepartment'
    let data = {
        'action':this.hrmDataService.setEmployeeDetails.action,
        'orgSlug': this.cookieService.get('orgSlug'),
        'departmentSlug': this.hrmDataService.setEmployeeDetails.departmentSlug,
        'employeeSlug': this.hrmDataService.setEmployeeDetails.employeeSlug,
        'isHead': this.hrmDataService.setEmployeeDetails.isHead,
      }
      let header = new HttpHeaders().set('Content-Type', 'application/json');
      let headers = { headers: header };
      // Preparing HTTP Call
      return this.http.post(URL, data, headers)
        .map(this.checkResponse)
        .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }

/* set employee to department[End] */

/* get all departments [Start] */
getAllDepartment(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/fetchAllDepartments'
  let data = {
    'orgSlug': this.cookieService.get('orgSlug'),
   }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get all departments [end] */

/* get holiday list [Start]*/
getHolidayList(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/fetchAllHolidays'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "page": this.hrmDataService.holiday.page,
    "sortBy": this.hrmDataService.holiday.sortOption,
    "sortOrder": this.hrmDataService.holiday.sortMethod,
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get holiday list[End] */

/* get holiday list [Start]*/
getEmployeeleave(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/fetchEmployeeLeaveInfo'
  let data = {
    "employeeSlug": this.hrmDataService.employee.empSlug,
    "year": this.hrmDataService.leaveList.levelist.year
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get holiday list[End] */

/* get holiday list [Start]*/
getAbcentChartList(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/absentChart'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "month": this.hrmDataService.absentChart.month,
    "year": this.hrmDataService.absentChart.year,
    "filter": {
      "departmentSlug": this.hrmDataService.absentChart.filter.departmentSlug,
      "leaveTypeSlugs": this.hrmDataService.absentChart.filter.leaveTypeSlugs
    }
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get holiday list[End] */

/* get holiday list [Start]*/
getLeaveList(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/fetchAllLeaveRequest'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "tab": this.hrmDataService.leaveList.tab,
    "sortBy": this.hrmDataService.leaveList.sortOption,
    "sortOrder": this.hrmDataService.leaveList.sortMethod
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get holiday list[End] */

/* get holiday list [Start]*/
setTrainingBudget(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/setTrainingBudget'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "amount": this.hrmDataService.trainingStatus.trainingBudget.totalBalance
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get holiday list[End] */

/* get holiday list [Start]*/
setTrainingFeedback(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/setTrainingFeedbackDuration'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "days": this.hrmDataService.trainingStatus.trainingFeedback.days
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get holiday list[End] */

/* get holiday list [Start]*/
getTrainingSettings(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/getTrainingSettings'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug')
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get holiday list[End] */

/* get holiday list [Start]*/
setStartTraining(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/setTrainingStatus'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "slug": this.hrmDataService.selectedApprovedRequest.slug,
    "status": "start"
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get holiday list[End] */

/* get holiday list [Start]*/
setTrainingStatus(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/setTrainingStatus'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "slug": this.hrmDataService.setTrainingStatus.slug,
    "status": this.hrmDataService.setTrainingStatus.status
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get holiday list[End] */


/* get holiday list [Start]*/
completeTraining(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/setTrainingStatus'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "slug": this.hrmDataService.selectedRequest.slug,
    "status": "completed"
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get holiday list[End] */



/* approve leave [Start]*/
approveLeave(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/approveLeaveRequest'
  let data = {
     "leaveRequestSlug": this.hrmDataService.requestDetails.leaveRequestSlug
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* approve leave [end] */

 /* get department list [Start]*/
 getDepartments(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/fetchAllDepartments'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get department list [End] */

 /* get company tree[Start]*/
 getCompanyTree(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/fetchDepartmentTree'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "departmentSlug": null
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get company tree [End] */

/* get department tree structure[Start]*/

 getDepartmentTree(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/getAllDepartmentsTree'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}

/* get department tree structure[End]*/




/* get department details[Start]*/
getDepartmentDetails(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/listDepartmentEmployees'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "departmentSlug": this.hrmDataService.departmentDetailsSlug.departmentSlug,
    // "sortBy": null,
    // "sortOrder": null,
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get department details[End]*/


/* get company sub tree[Start]*/
getCompanySubTree(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/fetchDepartmentTree'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "departmentSlug": this.hrmDataService.companySubTree.departmentSlug
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get company sub tree [End] */

/* get training request [Start]*/
getTrainingRequestList(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/getTrainingRequestList';
  let data = {
    'orgSlug': this.cookieService.get('orgSlug'),
    'tab': this.hrmDataService.getTrainingDetails.tab
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get training request [end] */

   /* delete employee[Start] */
   deleteEmployee(): Observable<any> {
    let URL = Configs.api + 'orgmanagement/employee/' + this.hrmDataService.employee.empSlug;
    // Preparing HTTP Call
    return this.http.delete(URL)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* delete employee[End] */

   /* create leave type[Start]*/
   createLeaveType(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/createLeaveType'
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "action": this.hrmDataService.leaveType.action,
      "leaveTypeSlug": this.hrmDataService.leaveType.leaveTypeSlug,
      "name": this.hrmDataService.leaveType.name,
      "type": this.hrmDataService.leaveType.type,
      "description": this.hrmDataService.leaveType.description,
      "isApplicableFor": this.hrmDataService.leaveType.isApplicableFor,
      "toUsers": this.hrmDataService.toUsers.slug,
      "is_to_all_employees": this.hrmDataService.toUsers.toAllEmployee,
      "leaveCount": this.hrmDataService.leaveType.leaveCount,
      "colorCode": this.hrmDataService.leaveType.colorCode
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* create leave type[end]*/

  /* create holiday [Start]*/
  createHoliday(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/createOrUpdateHoliday'
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "action": this.hrmDataService.holiday.action,
      "holidaySlug": this.hrmDataService.holiday.holidaySlug,
      "name": this.hrmDataService.holiday.name,
      "description": this.hrmDataService.holiday.info,
      "holidayDate": this.hrmDataService.holiday.holidayDate,
      "isRestricted": this.hrmDataService.holiday.isRestricted,
      "repeted": this.hrmDataService.holiday.repeted,
      "isRepeatYearly": this.hrmDataService.holiday.repeted,
     }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /*  create holiday [End] */

   /* create holiday [Start]*/
   updateProfile(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'usermanagement/edit-profile'
    let data = {
      "first_name": this.hrmDataService.getEmployeeDetails.name,
      "last_name": '',
      "birth_date": this.hrmDataService.getEmployeeDetails.birthDate,
       "additionalInfo": {
             "joiningDate": this.hrmDataService.getEmployeeDetails.joiningDate,
              "addressInfo": {
              "streetAddress": this.hrmDataService.getEmployeeDetails.streetAddress,
              "addressLine2":  this.hrmDataService.getEmployeeDetails.addressLine2,
              "city": this.hrmDataService.getEmployeeDetails.city,
              "state": this.hrmDataService.getEmployeeDetails.state,
              "countrySlug": this.hrmDataService.getEmployeeDetails.country,
              "zipcode": this.hrmDataService.getEmployeeDetails.zipcode
             }
         }
     }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /*  create holiday [End] */

  /* Create Department for a company [Start]*/
  createNewDept(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'orgmanagement/createDepartment'
    let data = this.hrmDataService.createDept;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Create Department for a company [End]*/

  /* Delete Department for a company [Start]*/
  deleteDept(): Observable<any> {
     // Preparing Post variables
     let URL = Configs.api + 'orgmanagement/deleteDepartment'
     let data = this.hrmDataService.deleteDepartment;
     let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* Delete Department for a company [End]*/

  // /* Edit Department for a company [Start]*/
  // editDept(): Observable<any> {
  //    // Preparing Post variables
  //    console.log('edit',this.hrmDataService.editDept)
  //   let URL = Configs.api + 'orgmanagement/updateDepartment'
  //   let data = this.hrmDataService.editDept;
  //   let header = new HttpHeaders().set('Content-Type', 'application/json');
  //   let headers = { headers: header };
  //   // Preparing HTTP Call
  //   return this.http.post(URL, data, headers)
  //     .map(this.checkResponse)
  //     .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  // }

  // /* Edit Department for a company [End]*/

  /* Edit Department for a company [Start]*/
  editDept(): Observable<any> {
    // Preparing Post variables
    console.log('edit',this.hrmDataService.editDept)
   let URL = Configs.api + 'orgmanagement/updateDepartment'
   let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "name": this.hrmDataService.editDept.name,
    "parentDepartmentSlug": this.hrmDataService.editDeptSlug.slug,
    "rootDepartmentSlug": this.hrmDataService.editDept.rootDepartmentSlug,
    "paretDeptName": this.hrmDataService.editDept.paretDeptName,
    "departmentHeadName": this.hrmDataService.editDept.departmentHeadName,
    "employeeSlug": this.hrmDataService.editDept.employeeSlug,
    "departmentSlug": this.hrmDataService.editDept.departmentSlug,
   } ;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
   let headers = { headers: header };
   // Preparing HTTP Call
   return this.http.post(URL, data, headers)
     .map(this.checkResponse)
     .catch((error) => Observable.throw(error.error.error || 'Server error.'));
 }

 /* Edit Department for a company [End]*/

  /* create absence [Start]*/
  createAbsence(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/createAbsent'
    let data = {
      "action": this.hrmDataService.absent.action,
      "orgSlug": this.cookieService.get('orgSlug'),
      "absentSlug": this.hrmDataService.absent.absentSlug,
      "absentUser": this.hrmDataService.toUsers.slug,
      "absentStartsOn": this.hrmDataService.absent.absentStartsOn,
      "absentEndsOn": this.hrmDataService.absent.absentEndsOn,
      "startsOnHalfDay": this.hrmDataService.absent.startsOnHalfDay,
      "endsOnHalfDay": this.hrmDataService.absent.endsOnHalfDay,
      "absentTypeSlug": this.hrmDataService.absent.absentTypeSlug,
      "reason": this.hrmDataService.absent.reason,
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* create absence [End] */

   /* create leave [Start]*/
   createLeave(): Observable<any> {
    // Preparing Post variables
    let URL = Configs.api + 'hrm/createOrCancelLeaveRequest'
    let data = {
      "orgSlug": this.cookieService.get('orgSlug'),
      "leaveRequestSlug": this.hrmDataService.leaveCreate.leaveSlug,
      "requestTo": this.hrmDataService.toUsers.slug,
      "requestStartsOn": this.hrmDataService.leaveCreate.leaveStartsOn,
      "requestEndsOn": this.hrmDataService.leaveCreate.leaveEndsOn,
      "isRequestStartsOnhalfday": this.hrmDataService.leaveCreate.startsOnHalfDay,
      "isRequestEndsOnhalfday": this.hrmDataService.leaveCreate.endsOnHalfDay,
      "leaveTypeSlug": this.hrmDataService.leaveCreate.leaveTypeSlug,
      "reason": this.hrmDataService.leaveCreate.reason,
      "action": this.hrmDataService.leaveCreate.action
    }
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };
    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error || 'Server error.'));
  }
  /* create leave[End] */

 /* invite employee[Start] */
 inviteEmployee(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/inviteEmployee';
  let data = {
    'orgSlug': this.cookieService.get('orgSlug'),
    'name': this.hrmDataService.employee.name,
    'email': this.hrmDataService.employee.email,
    'reportManagerEmpSlug': this.hrmDataService.employee.reportManagerEmpSlug,
   }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* invite employee[End] */

 /* register employee[Start] */
 registerEmployee(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/employee';
  let data = {
    'orgSlug': this.cookieService.get('orgSlug'),
    'name': this.hrmDataService.employee.name,
    'email': this.hrmDataService.employee.email,
    'departmentSlugs': this.hrmDataService.departmentList.slug,
    'reportManagerEmpSlug': this.hrmDataService.employee.reportManagerEmpSlug,
   }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* register employee[End] */

/* update employee[Start] */
updateEmployee(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'orgmanagement/employee/' +  this.hrmDataService.employee.empSlug;
  let data = {
    'orgSlug': this.cookieService.get('orgSlug'),
    'employeeSlug':  this.hrmDataService.employee.empSlug,
    'name': this.hrmDataService.employee.name,
    'departmentSlugs': this.hrmDataService.departmentList.slug,
    'reportManagerEmpSlug': this.hrmDataService.employee.reportManagerEmpSlug,
   }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.put(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* update employee[wnd] */

 /* creating training request [Start]*/
 createTrainingRequest(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/setTrainingRequest'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "action": this.hrmDataService.createTrainingRequest.action,
    "startsOn": this.utilityService.convertToUnix(this.hrmDataService.createTrainingRequest.startsOn),
    "endsOn": this.utilityService.convertToUnix(this.hrmDataService.createTrainingRequest.endsOn),
    "details": this.hrmDataService.createTrainingRequest.details,
   "name": this.hrmDataService.createTrainingRequest.name,
   "slug":this.hrmDataService.createTrainingRequest.slug,
   "approverEmployeeSlug":this.hrmDataService.createTrainingRequest.approverEmployeeSlug,
    "cost": this.hrmDataService.createTrainingRequest.cost,
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
 /* creating training request [end]*/

 /* creating training request [Start]*/
 approveTrainingRequest(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/setTrainingRequestStatus'
  let data = {
   "orgSlug": this.cookieService.get('orgSlug'),
   "slug":this.hrmDataService.approveTrainingRequest.slug,
   "status":this.hrmDataService.approveTrainingRequest.status,
   "hasFeedbackForm":this.hrmDataService.approveTrainingRequest.hasFeedbackForm,
   "forms": this.hrmDataService.approveTrainingRequest.forms
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
 /* creating training request [end]*/

 /* creating training request [Start]*/
 setTrainingRequestStatus(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/setTrainingRequestStatus'
  let data = {
   "orgSlug": this.cookieService.get('orgSlug'),
   "slug":this.hrmDataService.setTrainingRequestStatus.slug,
   "status":this.hrmDataService.setTrainingRequestStatus.status,
   "hasFeedbackForm":this.hrmDataService.setTrainingRequestStatus.hasFeedbackForm,
   "forms": this.hrmDataService.setTrainingRequestStatus.forms
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
 /* creating training request [end]*/

 /* creating Performance [Start]*/
 createPerformance(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/setKraModule'
  let data = {
    "orgSlug": this.cookieService.get('orgSlug'),
    "action": this.hrmDataService.createPerformance.action,
    "title":this.hrmDataService.createPerformance.title,
    "description":this.hrmDataService.createPerformance.description,
    "kraModuleSlug":this.hrmDataService.createPerformance.kraModuleSlug,
    "questions":this.hrmDataService.createPerformance.questions

  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
 /* creating Performance[end]*/

 /* get Performance [Start]*/
getPerformaceDetails(): Observable<any> {
  // Preparing Post variables
  let URL = Configs.api + 'hrm/getKraModule';
  let data = {
    'orgSlug': this.cookieService.get('orgSlug'),
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };
  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.error.error || 'Server error.'));
}
/* get Performance [end] */

 /* Getting  all forms[Start] */
 getAllForms(): Observable<any> {
  // Preparing Post variables

  let URL = Configs.api + 'formmanagement/fetchAllForms'
  let data = {
    "tab": this.hrmDataService.getAllForms.tab,
    "q": this.hrmDataService.getAllForms.searchTxt,
    "sortBy": this.hrmDataService.getAllForms.sortBy,
    "sortOrder": this.hrmDataService.getAllForms.sortOrder,
    "type":this.hrmDataService.getAllForms.type,
    "page": this.hrmDataService.getAllForms.page,
    "perPage": this.hrmDataService.getAllForms.perPage
  }
  let header = new HttpHeaders().set('Content-Type', 'application/json');
  let headers = { headers: header };

  // Preparing HTTP Call
  return this.http.post(URL, data, headers)
    .map(this.checkResponse)
    .catch((error) => Observable.throw(error.json().error || 'Server error.'));
}
/* Getting  all forms[End] */

  /* Generic function to check Responses[Start] */
  checkResponse(response: any) {
    let results = response
    if (results.status) {
      return results;
    }
    else {
     // console.log("Error in API");
      return results;
    }
  }
  /* Generic function to check Responses[End] */




  /*FORM REUSE[START]*/

  /* Creating new form[Start] */
  createNewFormPartial(): Observable<any> {
    // Preparing Post variables

    let URL = Configs.api + 'formmanagement/create'
    let data = this.hrmDataService.createHrmFormPartial;
    let header = new HttpHeaders().set('Content-Type', 'application/json');
    let headers = { headers: header };

    // Preparing HTTP Call
    return this.http.post(URL, data, headers)
      .map(this.checkResponse)
      .catch((error) => Observable.throw(error.error.error  || 'Server error.'));
  }
  /* Creating new form[End] */

    /*FORM REUSE[END]*/


}
