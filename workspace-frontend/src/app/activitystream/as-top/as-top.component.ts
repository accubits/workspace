import { Component, OnInit, HostListener } from '@angular/core';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../activity.sandbox';

@Component({
  selector: 'app-as-top',
  templateUrl: './as-top.component.html',
  styleUrls: ['./as-top.component.scss']
})
export class AsTopComponent implements OnInit {

  constructor(public actStreamDataService:ActStreamDataService,
    public activitySandboxService: ActivitySandboxService) { }

  ngOnInit() {
    this.actStreamDataService.fetchActivityStream.page = 1;
    this.actStreamDataService.fetchActivityStream.perPage = 10;
    this.actStreamDataService.fetchActivityStream.selectedTab = 'top';
    this.activitySandboxService.fetchActivityStream();
  }

   /* scroll pagination[Start] */
   @HostListener("window:scroll", ["$event"])
   onWindowScroll() {
   let pos = (document.documentElement.scrollTop || document.body.scrollTop) + document.documentElement.offsetHeight;
   let max = document.documentElement.scrollHeight;
   if(pos == max )   {
     this.actStreamDataService.fetchActivityStream.page = this.actStreamDataService.fetchActivityStream.page + 1; 
     this.actStreamDataService.fetchActivityStream.perPage = 5;
     this.actStreamDataService.fetchActivityStream.selectedTab = 'top';
     this.activitySandboxService.getActivityStream();
     }
   }
   /* scroll pagination[end] */
}
