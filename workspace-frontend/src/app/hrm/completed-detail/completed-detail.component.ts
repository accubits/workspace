import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-completed-detail',
  templateUrl: './completed-detail.component.html',
  styleUrls: ['./completed-detail.component.scss']
})
export class CompletedDetailComponent implements OnInit {
  training = 'detail';
  constructor(
    public hrmDataService: HrmDataService,
  ) { }

  ngOnInit() {
  }
  hideCompletedDetail() {
    this.hrmDataService.completedTrainingDetail.show = false;
  }
}
