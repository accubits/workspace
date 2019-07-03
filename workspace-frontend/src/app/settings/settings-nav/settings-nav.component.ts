import { Component, OnInit } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';

@Component({
  selector: 'app-settings-nav',
  templateUrl: './settings-nav.component.html',
  styleUrls: ['./settings-nav.component.scss']
})
export class SettingsNavComponent implements OnInit {

  checkRole:string;

  constructor(
    public cookieService: CookieService) {}

  ngOnInit() {
    this.checkRole= this.cookieService.get('role');
  }
}
