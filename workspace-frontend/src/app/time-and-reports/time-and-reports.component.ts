import { Component, OnInit } from '@angular/core';
import { Configs } from '../config';
import { TarDataService } from '../shared/services/tar-data.service';

@Component({
  selector: 'app-time-and-reports',
  templateUrl: './time-and-reports.component.html',
  styleUrls: ['./time-and-reports.component.scss']
})
export class TimeAndReportsComponent implements OnInit {
  tarFilter:boolean=false;
  tarNew:boolean=false;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public tarDataservice: TarDataService) { }

  ngOnInit() {
  }

}
