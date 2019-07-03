import { Component, OnInit } from '@angular/core';
import { Routes, RouterModule, Router, ActivatedRoute ,NavigationEnd,Event } from '@angular/router';
import { MenuToggle } from '../../shared/shared-logics';
import { Configs } from '../../config';
import { CookieService } from 'ngx-cookie-service';


@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  menutoggle: MenuToggle;
  menuItems: any[];
  partnerMenuItems: any[];
  activeClass:string;
  routerActivetab = '';
  currentRoute:string;
  checkpartner:string;
  currentUrl:string;


  constructor(menutoggle: MenuToggle, private router: Router,
    public cookieService: CookieService
  ) {
    this.menutoggle = menutoggle;
    this.router.events.subscribe(() => {
      this.currentRoute = this.router.url.split('/')[1];
      this.currentUrl = this.router.url;
      // console.log('current url',this.routerActivetab );

  })

    this.menuItems = [

      {
        dispName: 'Organisation',
        route: 'organisation',
        //imagePath: 'assets/images/nav/forms.png',
        dynamicClass:''
      },
      {
        dispName: 'License',
        route: 'license',
        //imagePath: 'assets/images/nav/tasks.png',
        dynamicClass:''
      },

      {
        dispName: 'Settings',
        route: 'partner-settings',
        //imagePath: 'assets/images/nav/crm.png',
        dynamicClass:''
      }

    ]
  }

  loadMenuModule(route) {
    this.router.navigate(['/partner/' + route]);
    this.routerActivetab = route;
  }

  ngOnInit() {
    this.checkpartner= this.cookieService.get('showPartner');

  }


}
