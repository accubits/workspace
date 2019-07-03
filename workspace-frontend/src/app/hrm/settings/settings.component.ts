import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.scss']
})
export class SettingsComponent implements OnInit {

  constructor(public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService) { }

  ngOnInit() {
    this.hrmSandboxService.getTrainingSettings();
  }

}
