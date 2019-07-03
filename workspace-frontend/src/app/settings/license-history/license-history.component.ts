import { SettingsDataService } from './../../shared/services/settings-data.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-license-history',
  templateUrl: './license-history.component.html',
  styleUrls: ['./license-history.component.scss']
})
export class LicenseHistoryComponent implements OnInit {

  constructor(
    public settingsDataService: SettingsDataService,
  ) { }

  ngOnInit() {
  }
  hideLicenseHistory() {
    this.settingsDataService.licenseHistory.show = false;
  }
}
