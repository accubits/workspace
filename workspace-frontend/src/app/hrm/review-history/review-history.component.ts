import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-review-history',
  templateUrl: './review-history.component.html',
  styleUrls: ['./review-history.component.scss']
})
export class ReviewHistoryComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
  ) { }

  ngOnInit() {
  }
  showDetailPop() {
    this.hrmDataService.ReviewHistoryPop.show = true;
  }
  showMoreOption() {
    event.stopPropagation();
    this.hrmDataService.moreOption.show = true;
  }
  hideMoreOption() {
    this.hrmDataService.moreOption.show = false;
  }
}
