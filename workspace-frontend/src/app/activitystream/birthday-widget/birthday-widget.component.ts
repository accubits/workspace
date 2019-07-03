import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../activity.sandbox';

@Component({
  selector: 'app-birthday-widget',
  templateUrl: './birthday-widget.component.html',
  styleUrls: ['./birthday-widget.component.scss']
})
export class BirthdayWidgetComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  
  constructor(public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService,) { }

  ngOnInit() {
  }
}
