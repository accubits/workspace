import { Component, OnInit } from '@angular/core';
import { HrmDataService } from '../../shared/services/hrm-data.service';
import { HrmSandboxService} from '../hrm.sandbox'
import { CookieService } from 'ngx-cookie-service';


@Component({
  selector: 'app-edit-dept',
  templateUrl: './edit-dept.component.html',
  styleUrls: ['./edit-dept.component.scss']
})
export class EditDeptComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    private hrmSandboxService :HrmSandboxService,
    private cookieService: CookieService


  ) { }

  parentShow : boolean = false;
  head : boolean = false;

  ngOnInit() {
    this.hrmSandboxService.getAllEmployee();
    this.hrmDataService.editDept.parentDepartmentSlug = this.hrmDataService.editDept.parentDepartmentSlug;
    console.log(this.hrmDataService.editDept.parentDepartmentSlug);
  }

  closePop(){
    this.hrmDataService.editDeptPop.show = false;
    this.hrmDataService.showEditDepartment.show = false
  }

  selectParentDepartment(dept){
    this.hrmDataService.editDept.paretDeptName = dept.departmentName;
    this.hrmDataService.editDept.parentDepartmentSlug = dept.departmentSlug;
   
  }

  headPop(){
    this.head =! this.head;
  }
  selectHeadOfDepartment(emp){
    this.hrmDataService.editDept.departmentHeadName = emp.employeeName;
    this.hrmDataService.editDept.employeeSlug = emp.employeeSlug;
  }
  parentPop(){
    this.parentShow =! this.parentShow;
  }
  closeParent(){
    this.parentShow = false;
  }
  closeHead(){
    this.head = false;
  }


  editNewDept(){
    this.hrmDataService.editDept.orgSlug = this.cookieService.get('orgSlug');
    this.hrmSandboxService.updateNewDept();
  }

}
