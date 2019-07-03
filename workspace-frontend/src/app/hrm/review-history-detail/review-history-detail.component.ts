import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-review-history-detail',
  templateUrl: './review-history-detail.component.html',
  styleUrls: ['./review-history-detail.component.scss']
})
export class ReviewHistoryDetailComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
  ) { }
  accordion = false;
  isValidated = false;
  isValidate = false;
  replyComment = false;
  accordiontwo = false;
  ngOnInit() {
    this.accordion = true;
  }
  showAccordion() {
    this.accordion = !this.accordion;
    this.isValidated = !this.isValidated;
  }
  showReplyComment() {
    this.replyComment = true;
  }
  hideDetailPop() {
    this.hrmDataService.ReviewHistoryPop.show = false;
  }
  showAccordionTwo() {
    this.accordiontwo = !this.accordiontwo;
    this.isValidate = !this.isValidate;
  }
}
