import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-annual-review-result',
  templateUrl: './annual-review-result.component.html',
  styleUrls: ['./annual-review-result.component.scss']
})
export class AnnualReviewResultComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
  ) { }

  ngOnInit() {
  }
  showDetail() {
    this.hrmDataService.annualReviewDetail.show = true;
  }
}
