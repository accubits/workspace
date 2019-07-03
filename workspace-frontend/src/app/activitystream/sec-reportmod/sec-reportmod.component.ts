import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';

@Component({
  selector: 'app-sec-reportmod',
  templateUrl: './sec-reportmod.component.html',
  styleUrls: ['./sec-reportmod.component.scss']
})
export class SecReportmodComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  constructor() { }

  ngOnInit() {
  }

}
