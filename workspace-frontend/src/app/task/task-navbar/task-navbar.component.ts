import { Component, OnInit } from '@angular/core';
import { TaskDataService } from '../../shared/services/task-data.service';
import { TaskSandbox } from '../task.sandbox';
import { Routes, RouterModule, Router,ActivatedRoute ,NavigationEnd,Event} from '@angular/router';


@Component({
  selector: 'app-task-navbar',
  templateUrl: './task-navbar.component.html',
  styleUrls: ['./task-navbar.component.scss']
})
export class TaskNavbarComponent implements OnInit {

  listPopup: boolean = false;
  mobPopup: boolean = false;
  actMore:boolean = false;
  moreOpt:string = 'More';
  routerActivetab:string;

  constructor(
    public taskDataService: TaskDataService,
    private taskSandbox: TaskSandbox,
    private router: Router,private route: ActivatedRoute
  ) { 
    this.router.events.subscribe((res) => { 
      this.routerActivetab = this.router.url.split('/')[3]
      if(this.routerActivetab === 'task-favourites'){
        this.moreOpt = 'Favorites'
      }
      if(this.routerActivetab === 'task-highpriority'){
        this.moreOpt = 'High Priority'
      }
      if(this.routerActivetab === 'task-completed'){
        this.moreOpt = 'Completed'
      }
      if(this.routerActivetab === 'task-all'){
        this.moreOpt = 'All'
      }
      if(this.routerActivetab === 'task-archive'){
        this.moreOpt = 'Archived'
      }
     
  })
 
  }

  ngOnInit() {

  }

  dropClose():void{
    this.mobPopup = false;
  }
  pageChanged($event):void{
    this.taskDataService.getTasks.page = $event;
    this.taskSandbox.getTaskList();
  }

  mobilePop(){
    this.mobPopup = !this.mobPopup;
    this.actMore = true;
  }

  menuTab(){
    this.actMore = false;
  }
  
  // moreOpts(routes):void{
  //   this.moreOpt = routes;
  // }

}
