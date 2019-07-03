import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-annual-review-result-detail',
  templateUrl: './annual-review-result-detail.component.html',
  styleUrls: ['./annual-review-result-detail.component.scss']
})
export class AnnualReviewResultDetailComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
  ) { }

  ngOnInit() {
  }
  hideDetail() {
    this.hrmDataService.annualReviewDetail.show = false;
  }
}
