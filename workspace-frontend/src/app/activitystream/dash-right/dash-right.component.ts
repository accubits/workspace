import { Component, OnInit } from '@angular/core';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../activity.sandbox';

@Component({
  selector: 'app-dash-right',
  templateUrl: './dash-right.component.html',
  styleUrls: ['./dash-right.component.scss']
})
export class DashRightComponent implements OnInit {

  constructor(public  actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService) { }

  ngOnInit() {
  }

}
