import { Component, OnInit } from '@angular/core';
import { PartnerSandbox } from './../partner.sandbox'

@Component({
  selector: 'app-settings-head',
  templateUrl: './settings-head.component.html',
  styleUrls: ['./settings-head.component.scss']
})
export class SettingsHeadComponent implements OnInit {

  viewContent : string = 'profile';
  profile : string = '';
  factor : string = '';
  
  constructor(
    public partnerSandbox:PartnerSandbox
  ) { }

  ngOnInit() {
    this.partnerSandbox.getSettings();
  }

}
