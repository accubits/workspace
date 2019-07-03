import { Component, OnInit } from '@angular/core';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';

@Component({
  selector: 'app-activity-stream-panel',
  templateUrl: './activity-stream-panel.component.html',
  styleUrls: ['./activity-stream-panel.component.scss']
})
export class ActivityStreamPanelComponent implements OnInit {
  constructor(public actStreamDataService: ActStreamDataService) { }
  ngOnInit() {
  }
}
