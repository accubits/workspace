import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-work-report-add',
  templateUrl: './work-report-add.component.html',
  styleUrls: ['./work-report-add.component.scss']
})
export class WorkReportAddComponent implements OnInit {
  showTaskList:boolean =false;  

  constructor() { }

  ngOnInit() {
  }
  closeWorkReport(): void{
    //this.clockDataService.clockManagement.showWorkReport = false;
    //new build
  }
  closeList(){
    this.showTaskList = false;
  }

}
