import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { TarDataService } from '../../shared/services/tar-data.service';

@Component({
  selector: 'app-wr-year',
  templateUrl: './wr-year.component.html',
  styleUrls: ['./wr-year.component.scss']
})
export class WrYearComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public tarDataservice: TarDataService) { }

  ngOnInit() {
  }

}
