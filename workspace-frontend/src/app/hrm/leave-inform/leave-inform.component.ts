import { Component, OnInit } from '@angular/core';
import { HrmDataService } from './../../shared/services/hrm-data.service';
import { HrmSandboxService } from '../hrm.sandbox';

@Component({
  selector: 'app-leave-inform',
  templateUrl: './leave-inform.component.html',
  styleUrls: ['./leave-inform.component.scss']
})
export class LeaveInformComponent implements OnInit {

  constructor(public hrmDataService: HrmDataService,
    public hrmSandboxService: HrmSandboxService) { }

  ngOnInit() {
   
  }

}
