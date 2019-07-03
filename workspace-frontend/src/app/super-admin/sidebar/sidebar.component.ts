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
  routerActivetab = 'license';

  constructor(menutoggle: MenuToggle, private router: Router, ) {
    this.menutoggle = menutoggle;

      this.menuItems = [
       {
         dispName: 'Dashboard',
         route: 'dashboard',
         //imagePath: 'assets/images/nav/activitystream.png',
         dynamicClass:''
       },
      {
        dispName: 'License',
        route: 'license',
        //imagePath: 'assets/images/nav/forms.png',
        dynamicClass:''
      },
      {
         dispName: 'Organization',
         route: 'organization',
         //imagePath: 'assets/images/nav/tasks.png',
         dynamicClass:''
       },
      //  {
      //   dispName: 'Workflow',
      //   route: '',
      //   //imagePath: 'assets/images/nav/timeandreports.png',
      //   dynamicClass:''
      // },

      {
        dispName: 'Forms',
        route: 'forms',
        //imagePath: 'assets/images/nav/settings.png',
        dynamicClass:''
      },

      {
        dispName: 'Partners',
        route: 'partner',
        //imagePath: 'assets/images/nav/settings.png',
        dynamicClass:''
      },

      {
        dispName: 'Sub Admins',
        route: 'sub-admin',
        //imagePath: 'assets/images/nav/settings.png',
        dynamicClass:''
      },

      {
        dispName: 'Country',
        route: 'country',
        //imagePath: 'assets/images/nav/settings.png',
        dynamicClass:''
      },

      {
        dispName: 'Vertical',
        route: 'vertical',
        //imagePath: 'assets/images/nav/settings.png',
        dynamicClass:''
      },

      // {
      //   dispName: 'Logs',
      //   route: '',
      //   //imagePath: 'assets/images/nav/settings.png',
      //   dynamicClass:''
      // },

      {
        dispName: 'Settings',
        route: 'settings',
        //imagePath: 'assets/images/nav/settings.png',
        dynamicClass:''
      }

    ]
  }

  loadMenuModule(route) {
    this.router.navigate(['/super-admin/' + route]);
    this.routerActivetab=route;
  }

  ngOnInit() {


  }





}
