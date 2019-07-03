import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {

  constructor(
    public router: Router,
  ) { }
  time = false;
  date = false;
  ngOnInit() {
  }
  showTime() {
    this.time = !this.time;
  }
  hideTime() {
    this.time = false;
  }
  showDate() {
    this.date = !this.date;
  }
  hideDate() {
    this.date = false;
  }
}
