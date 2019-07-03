import { HrmDataService } from './../../shared/services/hrm-data.service';
import { Component, OnInit } from '@angular/core';


@Component({
  selector: 'app-feedback-form',
  templateUrl: './feedback-form.component.html',
  styleUrls: ['./feedback-form.component.scss']
})
export class FeedbackFormComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
  ) { }

  ngOnInit() {
  }

}
