import { Component, OnInit } from '@angular/core';
import { PerfectScrollbarModule } from 'ngx-perfect-scrollbar';
import {PartnerSandbox} from '../partner/partner.sandbox';

@Component({
  selector: 'app-partner',
  templateUrl: './partner.component.html',
  styleUrls: ['./partner.component.scss'],
  host:{
    '(window:scroll)':'onScroll($event)'
  }
})
export class PartnerComponent implements OnInit {

  isScrolled = false;
  currPos: Number = 0;
  startPos: Number = 0;
  changePos: Number = 5;

  constructor(
    public partnerSandbox:PartnerSandbox
  ) { }

  onScroll(evt) {
    this.currPos = (window.pageYOffset || evt.target.scrollTop) - (evt.target.clientTop || 0);
    if(this.currPos >= this.changePos ) {
        this.isScrolled = true;
    } else {
        this.isScrolled = false;
    }
  }

  ngOnInit() {
    this.partnerSandbox.getRoleDetails();

  }

}
