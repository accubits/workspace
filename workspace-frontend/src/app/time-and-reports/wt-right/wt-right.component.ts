import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { TarDataService } from '../../shared/services/tar-data.service';

@Component({
  selector: 'app-wt-right',
  templateUrl: './wt-right.component.html',
  styleUrls: ['./wt-right.component.scss']
})
export class WtRightComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public tarDataservice: TarDataService) { }

  ngOnInit() {
  }

}
