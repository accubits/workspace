import { Component, OnInit } from '@angular/core';
import { HrmDataService} from '../../shared/services/hrm-data.service';
import { HrmSandboxService} from '../hrm.sandbox'
import { CookieService } from 'ngx-cookie-service';


@Component({
  selector: 'app-new-dept',
  templateUrl: './new-dept.component.html',
  styleUrls: ['./new-dept.component.scss']
})
export class NewDeptComponent implements OnInit {
  parentShow : boolean = false;
  head : boolean = false;
  selParentDept:string;
  selHeadOfDept:string;

  constructor(
    private hrmSandboxService :HrmSandboxService,
    public hrmDataService: HrmDataService,
    private cookieService: CookieService
  ) { }

  ngOnInit() {
    this.hrmSandboxService.getDepartments();
    this.hrmSandboxService.getAllEmployee();
  }

  closePop(){
    this.hrmDataService.deptPop.show = false;
  }

  parentPop(){
    this.parentShow =! this.parentShow;
  }
  closeParent(){
    this.parentShow = false;
  }
  headPop(){
    this.head =! this.head;
  }
  closeHead(){
    this.head = false;
  }

  /* Create new Department[Start] */
  createNewDept():void{
      this.hrmDataService.createDept.orgSlug = this.cookieService.get('orgSlug');
      this.hrmDataService.createDept.rootDepartmentSlug = this.hrmDataService.createDept.rootDepartmentSlug;
      if(this.hrmDataService.createDept.rootDepartmentSlug === null){      
        this.hrmDataService.createDept.rootDepartmentSlug = this.hrmDataService.createDept.parentDepartmentSlug;
      }
      this.hrmSandboxService.createNewDept();
      this.hrmSandboxService.getDepartmentTree();
  }
  /* Create new Department[End] */

  /* Select a parent department[Start] */
  selectParentDepartment(dept):void{
    this.hrmDataService.createDept.parentDepartmentSlug =  dept.departmentSlug;
    this.hrmDataService.createDept.rootDepartmentSlug = dept.rootDepartmentSlug;
    this.selParentDept = dept.departmentName;
  }


  /* Select a parent department[End] */


  /* Select a root department[Start] */
  selectHeadDepartment(emp):void{
    this.hrmDataService.createDept.employeeSlug =  emp.employeeSlug;
    this.selHeadOfDept = emp.employeeName;
  }
  /* Select a root department[End] */


}
