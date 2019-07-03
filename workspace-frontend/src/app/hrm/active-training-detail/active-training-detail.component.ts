import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-active-training-detail',
  templateUrl: './active-training-detail.component.html',
  styleUrls: ['./active-training-detail.component.scss']
})
export class ActiveTrainingDetailComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
  ) { }

  ngOnInit() {
  }
closeDetail() {
  this.hrmDataService.activeTrainingPop.show = false;
}
}
