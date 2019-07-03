import { HrmDataService } from './../../shared/services/hrm-data.service';
import {HrmSandboxService} from './../hrm.sandbox'
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-kra-modules',
  templateUrl: './kra-modules.component.html',
  styleUrls: ['./kra-modules.component.scss']
})
export class KraModulesComponent implements OnInit {

  moreOption: boolean = false;


  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService:HrmSandboxService
  ) { }

  ngOnInit() {
    this.hrmSandboxService.getAllPerformance();
  }
  

  showKraModule(i) {
  this.hrmDataService.kraModuleDetail.show = true;
  this.hrmDataService.selectedPerformance = this.hrmDataService.getPerformance.kraModules[i]
  // this.hrmDataService.createPerformance.questions = this.hrmDataService.selectedPerformance.questions;
  }


  showMoreOption(event,i) {
    event.stopPropagation();
     this.hrmDataService.getPerformance.kraModules[i]['showMore'] = !this.hrmDataService.getPerformance.kraModules[i]['showMore'];
    // this.moreOption = true;
  }

  showDelete(){
   
    this.hrmDataService.deleteConfirmPopup.show = true;
  }
  

  cancelPopup(event){
    event.stopPropagation();
    this.hrmDataService.deleteConfirmPopup.show = false;
  }

/*  Delete Performance [Start]  */
  deletePerformance(event,kraModuleSlug){
    event.stopPropagation();
    this.hrmDataService.createPerformance.action = 'delete'
    this.hrmDataService.createPerformance.kraModuleSlug = kraModuleSlug;
    this.hrmSandboxService.addPerformance();
    this.hrmDataService.deleteConfirmPopup.show = false;
  }
  /*  Delete Performance [End]  */


  /*Edit Popup[Start] */
  showEdit(performance){
    this.hrmDataService.showEditPopup.show = true;
    this.hrmDataService.createPerformance.action = 'update'
    this.hrmDataService.createPerformance.title = performance.title
    this.hrmDataService.createPerformance.description = performance.description;
    for(let i=0;i< performance.questions.length;i++)
    {
      performance.questions[i].action = 'update';

    }
    this.hrmDataService.createPerformance.questions = performance.questions;
    this.hrmDataService.createPerformance.kraModuleSlug = performance.slug;
  }

    /*Edit Popup[End] */



}
