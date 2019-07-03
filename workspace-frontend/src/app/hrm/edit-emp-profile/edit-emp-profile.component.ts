import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-edit-emp-profile',
  templateUrl: './edit-emp-profile.component.html',
  styleUrls: ['./edit-emp-profile.component.scss']
})
export class EditEmpProfileComponent implements OnInit {

   public todayDate: any = new Date();
  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }
  departList: boolean = false;
  interestList: boolean = false;
  reportingTo: boolean = false;
  intrestName: '';
  intrestArray: any;
  countryList = false;

  ngOnInit() {
    if(this.hrmDataService.getEmployeeDetails.birthDate !== null){
      this.hrmDataService.getEmployeeDetails.birthDate = new Date(this.hrmDataService.getEmployeeDetails.birthDate * 1000);
    }
    if(this.hrmDataService.getEmployeeDetails.joiningDate !== null){
      this.hrmDataService.getEmployeeDetails.joiningDate = new Date(this.hrmDataService.getEmployeeDetails.joiningDate * 1000);
    }
    this.intrestArray = this.hrmDataService.getEmployeeDetails.interest;
    this.hrmSandboxService.getAllDepartment();
    this.hrmSandboxService.getAllEmployee();
    this.hrmSandboxService.getCountryList();
  }
  departShow() {
    this.departList = true;
  }
  departHide() {
    this.departList = false;
  }
  showIntrst() {
    this.interestList = true;
  }
  hideIntrst() {
    this.interestList = false;
  }
  reportingPersonHide() {
    this.reportingTo = false;
  }
  reportingPersonShow() {
    this.reportingTo = true;
  }
  hideEditEmp() {
    this.hrmDataService.editEmpDetail.show = false;
  }
  addIntrest(){
    this.intrestArray.push({slug: null, title: this.intrestName});
    this.intrestName = '';
  }
  /* Select users list [Start] */
  selectEmployee(emp): void {
    this.hrmDataService.getEmployeeDetails.reportingManagerDetails = [];
    this.hrmDataService.getEmployeeDetails.reportingManagerDetails.push({'reportingManagerImgUrl': emp.employeeImage, 'reportingManagerName': emp.employeeName, 'reportingManagerSlug': emp.employeeSlug})
    this.hrmDataService.employee.reportManagerEmpSlug = emp.employeeSlug;
    this.reportingTo = false;
  }
  /* Select users list [End] */
  showCountry() {
    this.countryList = !this.countryList;
  }

  updateEmp(){
    this.hrmSandboxService.updateProfile();
  }

  removeImage(){
    alert('wsrwe')
    this.hrmDataService.getEmployeeDetails.imageUrl === null;
  }
}
