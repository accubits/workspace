import { Component, OnInit } from '@angular/core';
import { Routes, RouterModule, Router, ActivatedRoute, NavigationEnd , Event} from '@angular/router';

@Component({
  selector: 'app-performance-navbar',
  templateUrl: './performance-navbar.component.html',
  styleUrls: ['./performance-navbar.component.scss']
})
export class PerformanceNavbarComponent implements OnInit {

  constructor(
    public router: Router,
  ) { }

  ngOnInit() {
  }

}
