import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-review-request',
  templateUrl: './review-request.component.html',
  styleUrls: ['./review-request.component.scss']
})
export class ReviewRequestComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
  ) { }

  ngOnInit() {
  }
  showReviewDetailPop() {
    this.hrmDataService.reviewDetailPop.show = true;
  }
  showMoreOption() {
    event.stopPropagation();
    this.hrmDataService.moreOption.show = true;
  }
  hideMoreOption() {
    this.hrmDataService.moreOption.show = false;
  }
}
