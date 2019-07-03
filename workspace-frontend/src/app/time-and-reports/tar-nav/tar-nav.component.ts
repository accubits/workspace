import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { TarDataService } from '../../shared/services/tar-data.service';

@Component({
  selector: 'app-tar-nav',
  templateUrl: './tar-nav.component.html',
  styleUrls: ['./tar-nav.component.scss']
})
export class TarNavComponent implements OnInit {
  activeView = 'abscenceView';

  public assetUrl = Configs.assetBaseUrl;
  constructor(public tarDataservice: TarDataService) { }

  ngOnInit() {
  }

}
