import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-training',
  templateUrl: './training.component.html',
  styleUrls: ['./training.component.scss']
})
export class TrainingComponent implements OnInit {

  constructor(
    public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService
  ) { }

  ngOnInit() {
    this.hrmSandboxService.getTrainingSettings();
    this.hrmDataService.resetreportingManagerDetails();
    this.hrmSandboxService.getProfile();
   }

}
