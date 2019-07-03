import { Component, OnInit } from '@angular/core';
import { Routes, RouterModule, Router } from '@angular/router';
import { MenuToggle } from '../../shared/shared-logics';
import { Configs } from '../../config';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  menutoggle: MenuToggle;
  menuItems: any[];
  activeClass:string;
  routerActivetab = 'partner-content';

  constructor(menutoggle: MenuToggle, private router: Router, ) {
    this.menutoggle = menutoggle;

      this.menuItems = [
      // {
      //   dispName: 'Dashboard',
      //   route: '',
      //   //imagePath: 'assets/images/nav/activitystream.png',
      //   dynamicClass:''
      // },

      {
        dispName: 'Partners',
        route: 'partner-content',
        routeName: 'partner-content',
        //imagePath: 'assets/images/nav/settings.png',
        dynamicClass:''
      },

      {
        dispName: 'Settings',
        route: 'settings',
        //imagePath: 'assets/images/nav/settings.png',
        dynamicClass:''
      }

    ]
  }

  loadMenuModule(route) {
    this.router.navigate(['/partner-manager/' + route]);
    this.routerActivetab=route;
  }

  ngOnInit() {


  }





}
