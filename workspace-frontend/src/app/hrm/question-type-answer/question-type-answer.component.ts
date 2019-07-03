import { Component, OnInit,Input } from '@angular/core';
import {HrmDataService} from '../../shared/services/hrm-data.service'

@Component({
  selector: 'app-question-type-answer',
  templateUrl: './question-type-answer.component.html',
  styleUrls: ['./question-type-answer.component.scss']
})
export class QuestionTypeAnswerComponent implements OnInit {
  @Input() Index: number


  constructor(
    public hrmDataService:HrmDataService
  ) { }

  ngOnInit() {
 
}

}
