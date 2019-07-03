import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { HrmDataService } from '../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-add-appraisal-pop',
  templateUrl: './add-appraisal-pop.component.html',
  styleUrls: ['./add-appraisal-pop.component.scss']
})
export class AddAppraisalPopComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  selectSwitch : string;
  isValidated = true;
  addMemberPop : boolean = false;
  userlistingdrop :boolean = false;
  selectDeptOption : boolean = false;
  selectEmpOption : boolean = false;
  date: Date = new Date();
  settings = {
    bigBanner: true,
    timePicker: true,
    format: 'dd-MM-yyyy',
    defaultOpen: false,
    hour12Timer: true
  };
  startDate: null;
  endDate: null;
  processingStartDate: null;
  processingEndDate: null

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService) {}

  ngOnInit() {
    this.selectSwitch ='department';
    this.hrmSandboxService.getAllDepartment();
    this.hrmSandboxService.getAllEmployee();
  }

  selectType(type){
    this.hrmDataService.createAppraisalCycle.cycle.type = type;
  }

  addMember(){
    this.addMemberPop =! this.addMemberPop;
  }
  selectEmployee(){
    this.selectEmpOption = !this.selectEmpOption;
  }
  selectDept(){
    this.selectDeptOption = !this.selectDeptOption;
  }
  closePop(){
    this.hrmDataService.addAppraisal.show = false;
  }
  closeAddPop(){
    this.addMemberPop = false;
  }

   /* add shered user[start]*/
   selectDepartment(dept) {
    let deptExist = this.hrmDataService.createAppraisalCycle.applicable.departments.filter(
      dep => dep.departmentSlug === dept.departmentSlug)[0];
    if (deptExist) {
      return;
    }
    else {
      this.hrmDataService.createAppraisalCycle.applicable.departments.push({ departmentSlug: dept.departmentSlug, departmentName: dept.departmentName});
      dept.existing = true;
    } 
    this.userlistingdrop = false;
  }
  /* add shered user[start]*/

}
