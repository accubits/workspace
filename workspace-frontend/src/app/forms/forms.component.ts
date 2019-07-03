import { Component, OnInit, ViewChild, HostListener, Inject  } from '@angular/core';
import { DOCUMENT } from '@angular/platform-browser';
import { MenuToggle } from '../shared/shared-logics';
import { Configs } from '../config';

@Component({
  selector: 'app-forms',
  templateUrl: './forms.component.html',
  styleUrls: ['./forms.component.scss']
})
export class FormsComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  menutoggle: MenuToggle;

  constructor(menutoggle: MenuToggle) {
    this.menutoggle = menutoggle;
  }
  ngOnInit() {
  }
}
