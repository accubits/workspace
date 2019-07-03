import { HrmDataService } from './../../shared/services/hrm-data.service';
import {HrmSandboxService} from './../hrm.sandbox'
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-kra-module-detail',
  templateUrl: './kra-module-detail.component.html',
  styleUrls: ['./kra-module-detail.component.scss']
})
export class KraModuleDetailComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService:HrmSandboxService
  ) { }

  ngOnInit() {
    console.log('test', this.hrmDataService.selectedPerformance);
  }
  hideKraModule() {
    this.hrmDataService.kraModuleDetail.show = false;
  }

  /*Edit Popup */
  showEdit(){
    this.hrmDataService.showEditPopup.show = true;
    this.hrmDataService.createPerformance.action = 'update'
    this.hrmDataService.createPerformance.title = this.hrmDataService.selectedPerformance.title
    this.hrmDataService.createPerformance.description = this.hrmDataService.selectedPerformance.description;
    for(let i=0;i< this.hrmDataService.selectedPerformance.questions.length;i++)
    {
      this.hrmDataService.selectedPerformance.questions[i].action = 'update';

    }
    //this.hrmDataService.selectedPerformance.questions.action = 'update';
    this.hrmDataService.createPerformance.questions = this.hrmDataService.selectedPerformance.questions;
    this.hrmDataService.createPerformance.kraModuleSlug = this.hrmDataService.selectedPerformance.slug;
    console.log('ncajmjncajkmk',this.hrmDataService.selectedPerformance.questions)
  }

  /*Delete Performance[Start]*/
  deletePerformance():void{
    this.hrmDataService.createPerformance.action = 'delete'
    this.hrmDataService.createPerformance.kraModuleSlug = this.hrmDataService.selectedPerformance.slug;
    this.hrmSandboxService.addPerformance();

  }
    /*Delete Performance[End]*/


}

