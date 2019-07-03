import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-absence-filter',
  templateUrl: './absence-filter.component.html',
  styleUrls: ['./absence-filter.component.scss']
})
export class AbsenceFilterComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }
  
  departDrp: boolean = false;
  deptName: string;

  ngOnInit() {
    this.hrmDataService.absentChart.filter.departmentSlug = null;
    this.hrmSandboxService.getAllDepartment();
    this.hrmSandboxService.getLeaveTypeList();
   }
  showDepartName() {
    this.departDrp = true;
  }
  hideDepartName() {
    this.departDrp = false;
  }
  hideAbsenceFilter() {
    this.hrmDataService.absenceFilter.show = false;
  }
  selectDepartmet(dept){
    this.hrmDataService.absentChart.filter.departmentName = dept.departmentName;
    this.hrmDataService.absentChart.filter.departmentSlug = dept.departmentSlug;
  }
  selectedFile(fileSelect, type){
   if(fileSelect){
    this.hrmDataService.absentChart.filter.leaveTypeSlugs.push(type.leaveTypeSlug);
   }
   else {
   let selSlug = this.hrmDataService.absentChart.filter.leaveTypeSlugs.filter(
    file => file === type.leaveTypeSlugs)[0];
  let index = this.hrmDataService.absentChart.filter.leaveTypeSlugs.indexOf(selSlug);
  this.hrmDataService.absentChart.filter.leaveTypeSlugs.splice(index, 1);
   }
  }
  filterAbscentlist(){
    this.hrmSandboxService.getAbcentChartList();
    this.hrmDataService.absenceFilter.show = false;
  }
  resetFilter(){
    this.hrmDataService.absentChart.filter.leaveTypeSlugs = [];
    for(let i = 0; i< this.hrmDataService.leaveType.leaveTypeList.length; i++){
      this.hrmDataService.leaveType.leaveTypeList[i].fileSelect = false;
    }
    this.hrmDataService.absentChart.filter.departmentName = null;
    this.hrmDataService.absentChart.filter.departmentSlug = null;
  }
}
