import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';

@Component({
  selector: 'app-ab-left',
  templateUrl: './ab-left.component.html',
  styleUrls: ['./ab-left.component.scss']
})
export class AbLeftComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  constructor() { }

  ngOnInit() {
  }

}
