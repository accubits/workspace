import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { HrmDataService } from './../../shared/services/hrm-data.service';



@Component({
  selector: 'app-course-form',
  templateUrl: './course-form.component.html',
  styleUrls: ['./course-form.component.scss']
})
export class CourseFormComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public router: Router,
    private activatedRoute: ActivatedRoute
  ) { }

  ngOnInit() {
  }

  courseFeedback(){
   this.hrmDataService.showScorePopup.show = true;
  }

  submitCourseRating(){
   this.hrmDataService.trainingRequestSlug = this.hrmDataService.selectedRequest.slug
    this.router.navigate(['../../../forms/form_submit/'+ this.hrmDataService.selectedRequest.postTrainingFormSlug + '/null'],{ relativeTo: this.activatedRoute ,
      queryParams: { slug: this.hrmDataService.trainingRequestSlug }
   });
  }


  ngOnDestroy(){
    this.hrmDataService.showScorePopup.show = false;
  }

}
