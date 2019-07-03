import { Component, OnInit,HostListener } from '@angular/core';
import { PerfectScrollbarConfigInterface } from 'ngx-perfect-scrollbar';
 
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})

export class AppComponent {
  public config: PerfectScrollbarConfigInterface = {};
  title = 'app';

  public editorValue: string = '';

  constructor() { }

  OnInit() {

  }






}


