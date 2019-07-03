import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
import { ActivitySandboxService } from '../activity.sandbox';

@Component({
  selector: 'app-task-carousel-widget',
  templateUrl: './task-carousel-widget.component.html',
  styleUrls: ['./task-carousel-widget.component.scss']
})
export class TaskCarouselWidgetComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  bsyChecker = false;
  showPop:boolean = false;
  fromParent;

  prev(){
    if(this.bsyChecker){
      return
    }
    this.bsyChecker = true;
    var arr_prev = this.actStreamDataService.taskWidgetDetails.details.task[0];
    this.actStreamDataService.taskWidgetDetails.details.task[0]['status']='prev';
    console.log(this.actStreamDataService.taskWidgetDetails.details.task[0]['status']);
    this.actStreamDataService.taskWidgetDetails.details.task.splice(0,1);
    setTimeout(()=>{
      arr_prev['status'] = 'none';
      console.log(arr_prev)
      this.actStreamDataService.taskWidgetDetails.details.task.push(arr_prev);
      setTimeout(()=>{
        this.actStreamDataService.taskWidgetDetails.details.task[this.actStreamDataService.taskWidgetDetails.details.task.length - 1]['status'] = 'none';
      },50)
      this.bsyChecker = false;
    },300)
  }
  next(){
    if(this.bsyChecker){
      return
    }
    this.bsyChecker = true;
    // this.slider_array.push(this.slider_array.length+1);
    var arr_saver = this.actStreamDataService.taskWidgetDetails.details.task[this.actStreamDataService.taskWidgetDetails.details.task.length - 1];
    this.actStreamDataService.taskWidgetDetails.details.task[this.actStreamDataService.taskWidgetDetails.details.task.length - 1]['status']='next';
    setTimeout(()=>{
      this.actStreamDataService.taskWidgetDetails.details.task.splice(-1,1);
      arr_saver['status'] = 'none';
      console.log(arr_saver)
      this.actStreamDataService.taskWidgetDetails.details.task.unshift(arr_saver);
      this.bsyChecker = false;
    },300)
  }
  carouselStyle(idx) {
    for (let i = 0; i < idx; i++) {
        if(i<1){
          let carouselStyles = {
          //'background':'green',
          };
          return carouselStyles;
        }
        if(i<2){
          let carouselStyles = {
            //'background':'red',
            };
            return carouselStyles;
        }
        if(i<3){
          console.log("3");
        }
    }
  }

  constructor(public actStreamDataService: ActStreamDataService,
    public activitySandboxService: ActivitySandboxService,) { }

  ngOnInit() {
  }
  selectTask(event) {
    event.stopPropagation();
  }
  makeComplete(status, task, index): void {
    //event.stopPropagation();
    //alert("fsdf");
    this.actStreamDataService.taskRunManagement.selectedTaskIndex = index;
    this.actStreamDataService.taskRunManagement.selectedTaskIds.push({
      slug: task.slug
    });
    this.showPop = true;
  }
  cancelCompletion(): void {
    this.showPop = false;
    this.activitySandboxService.getTaskWidgetDetails();
    this.actStreamDataService.resetTaskRunManagement();
  }
  completeTask(): void {
    this.actStreamDataService.taskRunManagement.status = 'complete', 
    this.activitySandboxService.manageTaskStatus();
    this.showPop = false;
  }
}
