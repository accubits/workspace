import { Component, OnInit } from '@angular/core';
import { HrmDataService} from '../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-dept-pop',
  templateUrl: './dept-pop.component.html',
  styleUrls: ['./dept-pop.component.scss']
})
export class DeptPopComponent implements OnInit {

  constructor(
    public hrmDataService : HrmDataService,
    public hrmSandboxService: HrmSandboxService,
  ) { }

  removeDept : boolean = false;
  addMember : boolean = false;
  showMembers : boolean = false;
  hideMembers: boolean = false;
  deptList : boolean = false;

  ngOnInit() {
    //this.hrmSandboxService.getAllEmployee();
  }

  closePop(){
    this.hrmDataService.deptDetails.show = false;
  }

  showPop(idx){
    this.deptList[idx] =! this.deptList[idx];
  }
  deptScroll(){
    this.showMembers =true;
  }

  hideScroll(){
    this.showMembers = false;
  }
  closeAdd(){
    this.addMember = false;
  }
  add(){
    this.addMember =! this.addMember;
  }
  remove(event, idx){
    this.removeDept =! this.removeDept;
  }

  closeOption(){
    this.removeDept = false;
  }
  onSearchChange():void{
    this.hrmSandboxService.getAllEmployee();
  }
  addEmployeeToDepartment(emp):void{   
    this.hrmDataService.setEmployeeDetails.action = "create"
    this.hrmDataService.setEmployeeDetails.departmentSlug = this.hrmDataService.departmentDetailsSlug.departmentSlug;
    this.hrmDataService.setEmployeeDetails.employeeSlug = emp;
    this.hrmSandboxService.addMembersToDeptmnt();
    this.hrmSandboxService.getDpmntDetails();
    //console.log("dd");
  }
  deleteEmployeeToDepartment(empSlug):void{
    this.hrmDataService.setEmployeeDetails.action = "delete"
    this.hrmDataService.setEmployeeDetails.departmentSlug = this.hrmDataService.departmentDetailsSlug.departmentSlug;
    this.hrmDataService.setEmployeeDetails.employeeSlug = empSlug;
    this.hrmSandboxService.deleteMembersToDepartment();
    this.hrmSandboxService.getDpmntDetails();
  }
}
