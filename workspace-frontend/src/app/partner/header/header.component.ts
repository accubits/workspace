import { Component, OnInit ,ViewChild} from '@angular/core';
import { Inject, HostListener } from "@angular/core";
import { DOCUMENT } from '@angular/platform-browser';
import { MenuToggle } from '../../shared/shared-logics';
//import { TimeDateService } from '../services/time-date.service';
import { TimeDateService } from '../../shared/services/time-date.service';
import { Configs } from '../../config';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Routes, RouterModule, Router, ActivatedRoute } from '@angular/router';
import { AuthenticationService } from '../../shared/services/authentication.service';
import { CookieService } from 'ngx-cookie-service';
import { DataService } from '../../shared/services/data.service';
import { SettingsDataService } from '../../shared/services/settings-data.service';
import { SettingsApiService } from '../../shared/services/settings-api.service';
import merge from 'deepmerge'
import { PartnerDataService } from '../../shared/services/partner-data.service';
import { UtilityService } from '../../shared/services/utility.service';
// import { AuthorizedSandbox } from '../authorized.sandbox';
import { CdTimerComponent } from 'angular-cd-timer';
import { ToastService } from '../../shared/services/toast.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {
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

  checkpartner:string;
  
  timerStart:boolean =  false;
  constructor(menutoggle: MenuToggle,
    private timeDateService: TimeDateService,
    private router: Router,
    private cookieService: CookieService,
    private authenticationService: AuthenticationService,
    public dataService: DataService,
    public settingsDataService: SettingsDataService,
    public SettingsApiService: SettingsApiService,
    public partnerDataService : PartnerDataService,
    public utilityService : UtilityService,
    public toastService : ToastService,
    private http: HttpClient) {
    this.menutoggle = menutoggle;
  }

  selectTask() {
    event.stopPropagation();
  }

  ngOnInit() {
    this.date = this.timeDateService.getDate();
    this.presentDay = this.timeDateService.getDay();
    this.user = this.cookieService.get('userName');
    this.dataService.nav_head_height = document.getElementById('nav-head').offsetHeight;
    this.SettingsApiService.fetchProfileDetailsEdit().subscribe((result: any) => {
      console.log(result.data.message);

      this.settingsDataService.editSettingsTemplate = merge(this.settingsDataService.editSettingsTemplate, result.data);
    });
   // this.getCurrentClockStatus();
   this.checkpartner= this.cookieService.get('isPartner');
   console.log('vkmfkk',this.checkpartner);
 
  }
  
  logout() {
    this.authenticationService.logout(); // Calling logout functionlaity in auth service
  }

  /*Switch to partner manager*/
  
  switchPartnerManager(){
    this.router.navigate(['/partner-manager/partner-content']);
   

  }

  
  
}
