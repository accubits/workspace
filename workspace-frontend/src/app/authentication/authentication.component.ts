import { Component, OnInit } from '@angular/core';
import { Configs } from '../config';

@Component({
  selector: 'app-authentication',
  templateUrl: './authentication.component.html',
  styleUrls: ['./authentication.component.scss']
})
export class AuthenticationComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;

  constructor() { }

  ngOnInit() {
  }

}
