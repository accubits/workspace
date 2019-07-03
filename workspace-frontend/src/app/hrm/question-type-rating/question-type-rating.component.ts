import { Component, OnInit,Input } from '@angular/core';
import {HrmDataService} from '../../shared/services/hrm-data.service'

@Component({
  selector: 'app-question-type-rating',
  templateUrl: './question-type-rating.component.html',
  styleUrls: ['./question-type-rating.component.scss']
})
export class QuestionTypeRatingComponent implements OnInit {
  @Input() Index: number
  checkType:string



  constructor(
    public hrmDataService:HrmDataService
  ) { }

  ngOnInit() {
    // this.checkType = this.hrmDataService.createPerformance.questions[this.Index].enableComment 

  }

}
