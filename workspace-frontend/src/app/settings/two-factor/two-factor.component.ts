import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { SettingsDataService } from '../../shared/services/settings-data.service';

@Component({
  selector: 'app-two-factor',
  templateUrl: './two-factor.component.html',
  styleUrls: ['./two-factor.component.scss']
})
export class TwoFactorComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  constructor(
    public settingsDataService: SettingsDataService,
  ) { }

  ngOnInit() {
  }

}
