import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { TarDataService } from '../../shared/services/tar-data.service';

@Component({
  selector: 'app-ab-right',
  templateUrl: './ab-right.component.html',
  styleUrls: ['./ab-right.component.scss']
})
export class AbRightComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public tarDataservice: TarDataService) { }

  ngOnInit() {
  }

}
