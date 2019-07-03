import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { TarDataService } from '../../shared/services/tar-data.service';

@Component({
  selector: 'app-wr-week',
  templateUrl: './wr-week.component.html',
  styleUrls: ['./wr-week.component.scss']
})
export class WrWeekComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public tarDataservice: TarDataService) { }

  ngOnInit() {
  }

}
