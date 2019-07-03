import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-license-header',
  templateUrl: './license-header.component.html',
  styleUrls: ['./license-header.component.scss']
})
export class LicenseHeaderComponent implements OnInit {

  constructor(
    public router: Router,
  ) { }

  ngOnInit() {
  }

}
