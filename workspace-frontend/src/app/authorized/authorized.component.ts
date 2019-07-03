import { Component, OnInit, NgModule, HostListener, Inject } from '@angular/core';
//import { Inject, HostListener } from "@angular/core";
import { DOCUMENT } from "@angular/platform-browser";
import { MenuToggle } from '../shared/shared-logics';
import { Configs } from '../config';
import { DataService } from '../shared/services/data.service';

@Component({
  selector: 'app-authorized',
  templateUrl: './authorized.component.html',
  styleUrls: ['./authorized.component.scss'],
    host: {
      '(window:scroll)': 'onScroll($event)'
    }
})
export class AuthorizedComponent implements OnInit {

  private showPopup = false;
  public assetUrl = Configs.assetBaseUrl;
  menutoggle: MenuToggle;
  tabNo: string;
  window: any;
  task_Title: string;
  task_Description: string;
  task_DueDate: string;
  task_StartOn: string;
  task_Remind: string;
  task_ResponsiblePersons: object;
  task_Paricipants: object;
  task_CheckListItem: string;
  task_AddSubTask: string;
  task_AddParentTask: string;
  isScrolled = false;
  currPos: Number = 0;
  startPos: Number = 0;
  changePos: Number = 5;

  fb_chpos: Number = 155;


  constructor(menutoggle: MenuToggle, 
    public dataService: DataService,
  ) {
    this.menutoggle = menutoggle;
    this.tabNo = '';
  }





  /* @HostListener('scroll', ['$event'])
    onScroll(event) {
      this.currPos = (window.pageYOffset || event.target.scrollTop) - (event.target.clientTop || 0);
      if (this.currPos >= this.changePos) {
        this.isScrolled = true;
        console.log("scrolled");
      } else {
        this.isScrolled = false;
        console.log("left");
      }
    } */

    
  // public onScrollEvent(event: any): void {
  //   this.currPos = (window.pageYOffset || event.target.scrollTop) - (event.target.clientTop || 0);
  //   if (this.currPos >= this.changePos) {
  //     this.isScrolled = true;
  //     console.log('true');
  //   } else {
  //     this.isScrolled = false;
  //     console.log('false');
  //   }
  // }



  onScroll(evt) {
    this.currPos = (window.pageYOffset || evt.target.scrollTop) - (evt.target.clientTop || 0);
    if(this.currPos >= this.changePos ) {
        this.isScrolled = true;
    } else {
        this.isScrolled = false;
    }
  }



  ngOnInit() {

  }


  openDialog(tab) {
    if (tab === 'tab2') {
      this.tabNo = '2';
    }
  }

}



