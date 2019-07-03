import { Component, OnInit } from '@angular/core';
import { HrmDataService} from '../../shared/services/hrm-data.service';
import { HrmSandboxService} from '../hrm.sandbox'
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-add-child-department',
  templateUrl: './add-child-department.component.html',
  styleUrls: ['./add-child-department.component.scss']
})
export class AddChildDepartmentComponent implements OnInit {
  selHeadOfDept:string;
  head : boolean = false;

  constructor(
    private hrmSandboxService :HrmSandboxService,
    public hrmDataService: HrmDataService,
    private cookieService: CookieService
  ) { }

  ngOnInit() {
    this.hrmSandboxService.getAllEmployee();
  }
  selectHeadDepartment(emp):void{
    this.hrmDataService.createDept.employeeSlug =  emp.employeeSlug;
    this.selHeadOfDept = emp.employeeName;
  }
  headPop(){
    this.head =! this.head;
  }
  closeHead(){
    this.head =! this.head;
  }
  closePop(){
    this.hrmDataService.showAddChild.show = false;
    this.hrmDataService.resetCreateDept();
  }
  /* Create new Department[Start] */
  createNewDept():void{
    this.hrmDataService.createDept.orgSlug = this.cookieService.get('orgSlug');
    this.hrmSandboxService.createNewDept();
    //this.hrmSandboxService.getDepartmentTree();
    this.hrmDataService.showAddChild.show = false;
    this.hrmDataService.resetCreateDept();
}
/* Create new Department[End] */
}
