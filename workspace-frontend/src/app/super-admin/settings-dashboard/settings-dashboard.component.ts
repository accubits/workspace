import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-settings-dashboard',
  templateUrl: './settings-dashboard.component.html',
  styleUrls: ['./settings-dashboard.component.scss']
})
export class SettingsDashboardComponent implements OnInit {

  constructor() { }
  viewContent = 'dashboard';
  timeZone = false;
  ngOnInit() {
  }
  showTime() {
    this.timeZone = !this.timeZone;
  }
  hideTime() {
    this.timeZone = false;
  }
}
