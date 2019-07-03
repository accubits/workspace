import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-settings-head',
  templateUrl: './settings-head.component.html',
  styleUrls: ['./settings-head.component.scss']
})
export class SettingsHeadComponent implements OnInit {

  viewContent : string = 'profile';
  profile : string = '';
  factor : string = '';
  
  constructor() { }

  ngOnInit() {
    this.viewContent === 'profile';
  }

}
