import { Component, OnInit } from '@angular/core';
import { Configs } from '../config';
import { ActStreamDataService } from '../shared/services/act-stream-data.service';
import { ActivitySandboxService } from './activity.sandbox';

@Component({
  selector: 'app-activitystream',
  templateUrl: './activitystream.component.html',
  styleUrls: ['./activitystream.component.scss']
})
export class ActivitystreamComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;

  constructor(
    public actStreamDataService: ActStreamDataService,
    private activitySandboxService: ActivitySandboxService) {  }

  ngOnInit() {
  }

  goTop(){
    window.scroll(0,0);
    this.actStreamDataService.fetchActivityStream.scrollArrow = false;
  }
}
