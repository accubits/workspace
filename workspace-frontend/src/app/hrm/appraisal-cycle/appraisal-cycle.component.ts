import { Component, OnInit } from '@angular/core';
import { HrmDataService} from '../../shared/services/hrm-data.service';

@Component({
  selector: 'app-appraisal-cycle',
  templateUrl: './appraisal-cycle.component.html',
  styleUrls: ['./appraisal-cycle.component.scss']
})
export class AppraisalCycleComponent implements OnInit {

  constructor(
    public hrmDataService : HrmDataService
  ) { }

  ngOnInit() {
  }

  appraisalAnnual(){
    this.hrmDataService.appraisalReview.show = true;
  }

  more(){
    this.hrmDataService.moreOption.show = true;
  }

  closeMore(){
    this.hrmDataService.moreOption.show = false;
  }
}
