import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { TarDataService } from '../../shared/services/tar-data.service';

@Component({
  selector: 'app-wr-right',
  templateUrl: './wr-right.component.html',
  styleUrls: ['./wr-right.component.scss']
})
export class WrRightComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public tarDataservice: TarDataService) { }

  ngOnInit() {
  }

}
