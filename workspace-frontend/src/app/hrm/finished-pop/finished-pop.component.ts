
import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { DataService } from './../../shared/services/data.service';

@Component({
  selector: 'app-finished-pop',
  templateUrl: './finished-pop.component.html',
  styleUrls: ['./finished-pop.component.scss']
})
export class FinishedPopComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public router: Router,
    private activatedRoute: ActivatedRoute,
    public dataService: DataService,
  ) { }

  ngOnInit() {
  }
  closeFinishedTraining() {
    this.hrmDataService.finishedPop.show = false;
  }
  giveFeedback(){
    this.hrmDataService.trainingRequestSlug = this.hrmDataService.selectedRequest.slug
    this.router.navigate(['../../../forms/form_submit/'+ this.hrmDataService.selectedRequest.postTrainingFormSlug + '/null'],{ relativeTo: this.activatedRoute ,
       queryParams: { slug: this.hrmDataService.trainingRequestSlug }
    });
  }
}
