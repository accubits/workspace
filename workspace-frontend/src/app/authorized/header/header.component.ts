import { Component, OnInit, ViewChild, } from '@angular/core';
import { Inject, HostListener } from "@angular/core";
import { DOCUMENT } from '@angular/platform-browser';
import { MenuToggle } from '../../shared/shared-logics';
//import { TimeDateService } from '../services/time-date.service';
import { TimeDateService } from '../../shared/services/time-date.service';
import { Configs } from '../../config';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Routes, RouterModule, Router, ActivatedRoute, NavigationStart } from '@angular/router';
import { AuthenticationService } from '../../shared/services/authentication.service';
import { CookieService } from 'ngx-cookie-service';
import { DataService } from '../../shared/services/data.service';
import { SettingsDataService } from '../../shared/services/settings-data.service';
import { SettingsApiService } from '../../shared/services/settings-api.service';
import merge from 'deepmerge'
import { PartnerDataService } from '../../shared/services/partner-data.service';
import { ClockDataService } from '../../shared/services/clock.data.service';
import { UtilityService } from '../../shared/services/utility.service';
import { AuthorizedSandbox } from '../authorized.sandbox';
import { CdTimerComponent } from 'angular-cd-timer';
import { ToastService } from '../../shared/services/toast.service';
import { ClockApiService } from '../../shared/services/clock-api.service'

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})


export class HeaderComponent implements OnInit {
  @ViewChild('basicTimer') timer;

//   @HostListener("window:beforeunload", ["$event"]) unloadHandler(event: Event) {
//     this.clockDataService.alCheck.show = true;
//     console.log("Processing beforeunload...");
//     event.returnValue = false;
// }

  

  workReport: boolean = false;
  showSearch: boolean = false;
  ch_drop: boolean = false;
  account: boolean = false;
  workStatus: boolean = false;
  public assetUrl = Configs.assetBaseUrl;
  menutoggle: MenuToggle;
  date: any;
  presentDay: any;
  user: string
  searchWord: string;
  editShow: boolean = false;
  startTime = 0;
  

  //   @HostListener("window:beforeunload", ["$event"]) unloadHandler(event: Event) {
  //     this.doClockin()
  //     event.returnValue = true;
  // }


  timerStart: boolean = false;
  constructor(menutoggle: MenuToggle,
    private timeDateService: TimeDateService,
    private router: Router,
    private cookieService: CookieService,
    private authenticationService: AuthenticationService,
    public dataService: DataService,
    public settingsDataService: SettingsDataService,
    public SettingsApiService: SettingsApiService,
    public partnerDataService: PartnerDataService,
    public clockDataService: ClockDataService,
    public authorizedSandbox: AuthorizedSandbox,
    public utilityService: UtilityService,
    public clockApiService: ClockApiService,
    public toastService: ToastService,
    private http: HttpClient) {
    this.menutoggle = menutoggle;
    window.onbeforeunload = (ev) => {
      //alert('goin to refresh');
     return false;
    };



  }

  selectTask() {
    event.stopPropagation();
  }

  ngOnInit() {
    //alert("ffsdf");
    this.date = this.timeDateService.getDate();
    this.presentDay = this.timeDateService.getDay();
    this.user = this.cookieService.get('userName');
    this.dataService.nav_head_height = document.getElementById('nav-head').offsetHeight;
    this.SettingsApiService.fetchProfileDetailsEdit().subscribe((result: any) => {
      console.log(result.data.message);

      this.settingsDataService.editSettingsTemplate = merge(this.settingsDataService.editSettingsTemplate, result.data);
    });
    this.getCurrentClockStatus();
    // this.testcall();
    this.clockDataService.clockManagement.stratTime = this.clockDataService.clockStatus.lastRecordedTime;
    this.clockDataService.clockStatus.isWorkReportPrompt = true;
  }
  closeOverlay() {
    this.clockDataService.clockManagement.workReport = false;
  }

  // testcall(){
  //   this.clockApiService.getClockStatus().subscribe((result)=>{
  //      console.log(result);
  //   },(error)=>{
  //     console.log(error);
  //   })
  // }


  /* Getting current Clock Status[Start] */
  getCurrentClockStatus(): void {
    this.clockDataService.clockCurrentstatus.currentTime = this.utilityService.convertToUnix(new Date());
    this.authorizedSandbox.getCurrentClockStatus().subscribe((result: any) => {

      if (result.status === 'OK') {
        this.clockDataService.clockStatus = result.data;
        if (this.clockDataService.clockStatus.currentStatusName === 'earlyClockout'||this.clockDataService.clockStatus.currentStatusName === 'clockIn' || this.clockDataService.clockStatus.currentStatusName === 'pause' || this.clockDataService.clockStatus.currentStatusName === 'clockContinue') {
          this.clockDataService.clockManagement.stratTime = this.clockDataService.clockStatus.lastRecordedTime;
          this.clockDataService.clockManagement.showTimer = true;
          if(this.clockDataService.clockStatus.currentStatusName !== 'earlyClockout'){
            this.startClock();
          }
        }
        if (this.clockDataService.clockStatus.currentStatusName === 'clockOut') {
          this.timer.reset();
        }
      }

    },
      err => {
        this.toastService.Error('', err.msg)
      })

  }
  /* Getting current Clock Status[End] */

  logout() {
    this.authenticationService.logout(); // Calling logout functionlaity in auth service
  }

  /* Starting the clock */
  startClock(): void {
    this.clockDataService.clockManagement.showTimer = true;
    setTimeout(() => {
      this.timer.reset()
      if (this.clockDataService.clockStatus.currentStatusName === 'pause') {
        this.timer.stop();
        return;
      }
      this.timer.start();
    }, 100);

  }

  /* Stop/pause the clock */
  stopOrPauseClock(status): void {
    status === 'pause' ? this.timer.stop() : this.timer.reset();
  }

  /* Stop/resume the clock */
  stopOrResumeClock(status): void {
    status === 'clockContinue' ? this.timer.resume() : this.timer.reset();
  }

  /* Stop/resume the clock */
  stopOrContinue(): void {

    setTimeout(() => {
      this.clockDataService.clockManagement.showTimer = true;
      this.timer.start();
    }, 300);
  }

  /* clockOut PreviosDay */
  clockOutPreviosDay(): void {
    this.clockDataService.clockManagement.stratTime = 0;
    this.timer.reset();
  }

  /* toogle clock display */
  showClocks(): void {
    this.clockDataService.clockManagement.workReport = !this.clockDataService.clockManagement.workReport
  }

  updateTimer($event): void {
    this.clockDataService.clockTimer = $event;
    this.clockDataService.clockInput.lastRecordedTime = $event.tick_count;
  }

  updateStopishTime($event): void {
    this.clockDataService.clockInput.lastRecordedTime = $event.tickCounter;
  }





  doClockin(): void {
    this.clockDataService.clockInput.status = 'clockIn';
    this.authorizedSandbox.getClockStatus().subscribe((result: any) => {

      if (result.status === 'OK') {
        this.clockDataService.clockStatus = result.data;

      }

      this.clockDataService.resetClockInput();
    },
      err => {
        this.toastService.Error('', err.msg)
      })
  }





}
