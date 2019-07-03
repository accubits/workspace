import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-review-request-detail',
  templateUrl: './review-request-detail.component.html',
  styleUrls: ['./review-request-detail.component.scss']
})
export class ReviewRequestDetailComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
  ) { }
  ratingList: boolean = false;
  isValidated: boolean = false;
  reviewDetail: boolean = false;
  managementDetail: boolean = false;
  isValidate: boolean = false;
  ngOnInit() {
    this.reviewDetail = true;
  }
  showRatingList() {
this.ratingList = !this.ratingList;
  }
  showReviewDetail() {
    this.reviewDetail = !this.reviewDetail;
    this.isValidated = !this.isValidated;
  }
  hideRatingList()  {
    this.ratingList = false;
  }
  hideReviewDetailPop() {
    this.hrmDataService.reviewDetailPop.show = false;
  }
  showManagementDetail() {
    this.managementDetail = !this.managementDetail;
    this.isValidate = !this.isValidate;
  }
}
