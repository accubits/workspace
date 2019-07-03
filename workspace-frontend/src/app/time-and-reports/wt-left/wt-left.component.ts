import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { TarDataService } from '../../shared/services/tar-data.service';


@Component({
  selector: 'app-wt-left',
  templateUrl: './wt-left.component.html',
  styleUrls: ['./wt-left.component.scss']
})
export class WtLeftComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public tarDataservice: TarDataService) { }

  ngOnInit() {
  }

}
