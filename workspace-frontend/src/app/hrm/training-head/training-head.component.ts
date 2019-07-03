import { Component, OnInit } from '@angular/core';
import { Routes, RouterModule, Router, ActivatedRoute, NavigationEnd , Event} from '@angular/router';
import { HrmDataService} from '../../shared/services/hrm-data.service';
import { DataService } from './../../shared/services/data.service'

@Component({
  selector: 'app-training-head',
  templateUrl: './training-head.component.html',
  styleUrls: ['./training-head.component.scss']
})
export class TrainingHeadComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public dataService:DataService,
    public router: Router,
  ) { }

  ngOnInit() {
   }
  showTrainReqForm() {
    this.hrmDataService.trainingRequestForm.show = true;
  }

  /* show feedback form*/
  showFeedbackForm(){
    //this.dataService.new_form.show = true;
    this.dataService.hrmnew_form.show = true;
   // this.hrmDataService.showFeedbackForm.show = true;

  }
  // showFormCreationBlock(){
  //   // this.dataService.hrmnew_form.show = true;
  //   this.hrmDataService.newFormCreation.show = true;
  // }

}
