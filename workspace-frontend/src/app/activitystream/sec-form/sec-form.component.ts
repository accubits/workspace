import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service'
import { Router, ActivatedRoute } from '@angular/router';
import { ActivitySandboxService } from '../activity.sandbox';
import { CookieService } from 'ngx-cookie-service';


@Component({
  selector: 'app-sec-form',
  templateUrl: './sec-form.component.html',
  styleUrls: ['./sec-form.component.scss']
})
export class SecFormComponent implements OnInit {
  @Input() data: any;
  @Input() index: number;
  commentCreatorUserName: string;
  public assetUrl = Configs.assetBaseUrl;
  constructor( public actStreamDataService: ActStreamDataService,
    public router: Router,
    private activatedRoute: ActivatedRoute,
    private cookieService: CookieService,
    public activitySandboxService: ActivitySandboxService
  ) { }
  formActivity:any = {};

  ngOnInit() {
    setTimeout(() => {
      this.formActivity =  this.data;
    }, 100);
  }

  /* Going to form from activity stream[Start] */
  goToFormSubmit():void{
    this.router.navigate(['../../forms/form_submit/'+ this.formActivity.form.formSlug + '/' + this.formActivity.form.answersheetSlug], { relativeTo: this.activatedRoute });
  } 
  /* Going to form from activity stream[End] */
 }
