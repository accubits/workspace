import { Component, OnInit } from '@angular/core';
import { HrmDataService} from '../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-emp-table-head',
  templateUrl: './emp-table-head.component.html',
  styleUrls: ['./emp-table-head.component.scss']
})
export class EmpTableHeadComponent implements OnInit {

  showSearch: boolean = false;
  legend = false;
  constructor(  public hrmDataService : HrmDataService,
    public hrmSandboxService: HrmSandboxService) { }

  ngOnInit() {
  }

   /* search file[start]*/
   onSearchChange() {
    this.hrmSandboxService.getAllEmployee();
  }
  /*  search file[end]*/

   /* close search popup[start]*/
   searchClose() {
    this.hrmDataService.employeeList.searchEmpTxt = '';
    this.hrmSandboxService.getAllEmployee();
  }
  /* close search popup[end]*/
  showLegend() {
    this.legend = true;
  }
  hideLegend() {
    this.legend = false;
  }
}
