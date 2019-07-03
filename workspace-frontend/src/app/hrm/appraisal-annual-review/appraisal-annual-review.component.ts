import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-appraisal-annual-review',
  templateUrl: './appraisal-annual-review.component.html',
  styleUrls: ['./appraisal-annual-review.component.scss']
})
export class AppraisalAnnualReviewComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
  ) { }
  tab = 'details';
  tabdetails = 'application';
  ngOnInit() {
  }
  hidePopup() {
    this.hrmDataService.appraisalReview.show = false;
  }
}
