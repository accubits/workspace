import { Component, OnInit } from '@angular/core';
import { Routes, RouterModule, Router, ActivatedRoute , NavigationEnd, Event} from '@angular/router';
import { MenuToggle } from '../../shared/shared-logics';
import { Configs } from '../../config';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  toggleButton: boolean = false;
  menutoggle: MenuToggle;
  menuItems: any[];
  activeClass: string;
  routerActivetab = 'activity';
  subMenuActive = 'Employees';

  constructor(menutoggle: MenuToggle, private router: Router, private route: ActivatedRoute ) {
    this.menutoggle = menutoggle;
    this.router.events.subscribe((res) => {
        this.routerActivetab = this.router.url.split('/')[2];


        // console.log('routei',this.routerActivetab);
    })

      this.menuItems = [
      {
        dispName: 'Activity Stream',
        route: 'activity',
        routeName: 'activity',
        //imagePath: 'assets/images/nav/activitystream.png',
        dynamicClass:'act_stream',



      },
      {
        dispName: 'Forms',
        route: 'forms',
        routeName: 'forms',
        //imagePath: 'assets/images/nav/forms.png',
        dynamicClass:'act_form'
      },
      {
        dispName: 'Task',
        route: 'task/task-d',
        routeName: 'task',
        //imagePath: 'assets/images/nav/tasks.png',
        dynamicClass:'act_task'
      },
      // {
      //   dispName: 'Chats',
      //   route: 'chats',
      //   //imagePath: 'assets/images/nav/chats.png',
      //   dynamicClass:'act_chats'
      // },
      {
        dispName: 'crm',
        route: 'crm',
        routeName: 'crm',
        //imagePath: 'assets/images/nav/crm.png',
        dynamicClass:'act_crm'
      },
      // {
      //   dispName: 'work flow',
      //   route: 'workFlow',
      //   //imagePath: 'assets/images/nav/workflow.png',
      //   dynamicClass:'act_work'
      // },
      {
        dispName: 'calendar',
        route: 'calendar',
        //imagePath: 'assets/images/nav/calender.png',
        dynamicClass:'act_calendar'
      },
      // {
      //   dispName: 'calendar',
      //   route: 'calendar',
      //   //imagePath: 'assets/images/nav/calender.png',
      //   dynamicClass:'act_calendar'
      // },
      {
        dispName: 'hrm',
        route: 'hrm',
        routeName: 'hrm',
        //imagePath: 'assets/images/nav/hrm.png',
        dynamicClass:'act_hrm',

        hrmSubMenu: [
          {
            dispName: 'Employees',
            route: 'employee',
            routeName: 'emp',
            //imagePath: 'assets/images/nav/hrm.png',
            dynamicClass:'',
          },
          {
            dispName: 'Leave',
            route: 'leave',
            routeName: 'lev',
            //imagePath: 'assets/images/nav/hrm.png',
            dynamicClass:'',
          },
          {
            dispName: 'Training',
            route: 'training',
            //imagePath: 'assets/images/nav/hrm.png',yyyyyyyy
            dynamicClass:'',
          },
          {
            dispName: 'Performance',
            route: 'performance',
            //imagePath: 'assets/images/nav/hrm.png',
            dynamicClass:'',
          },
          {
            dispName: 'Settings',
            route: 'settings',
            //imagePath: 'assets/images/nav/hrm.png',
            dynamicClass:'',
          }
        ]
      },



      // // {
      // //   dispName: 'company',
      // //   route: 'company',
      // //   //imagePath: 'assets/images/nav/company.png',
      // //   dynamicClass:'act_company'
      // // },
      {
        dispName: 'drive',
        route: 'drive',
        routeName: 'drive',
        //imagePath: 'assets/images/nav/drive.png',
        dynamicClass: 'act_drive'
      },
      // {
      //   dispName: 'time and reports old',
      //   route: 'timeAndReports',
      //   //imagePath: 'assets/images/nav/timeandreports.png',
      //   dynamicClass:'act_time'
      // },
       {
        dispName: 'time and reports',
        route: 'timeReports',
        routeName: 'timeReports',
        //imagePath: 'assets/images/nav/timeandreports.png',
        dynamicClass:'act_time'
      },
      // {
      //   dispName: 'logs',
      //   route: 'logs',
      //   //imagePath: 'assets/images/nav/logs.png',
      //   dynamicClass:'act_logs'
      // },
      {
        dispName: 'settings',
        route: 'settings',
        routeName: 'settings',
        //imagePath: 'assets/images/nav/settings.png',
        dynamicClass:'act_settings'
      }
    ]
  }

  loadMenuModule(route) {
    this.router.navigate(['/authorized/' + route]);
   // this.routerActivetab=route;
   if(this.routerActivetab !== 'hrm') {
    this.toggleButton = false;
   }

  }

  loadHRMsubMenu(route, event, name) {
    this.subMenuActive = name;
    event.stopPropagation();
    this.router.navigate(["./hrm/" + route ], { relativeTo: this.route });
    // this.routerActivetab = this.router.url.split('/hrm')[2];

  }
  ngOnInit() {


  }

  toggle() {
    this.toggleButton = !this.toggleButton;
  }


}
