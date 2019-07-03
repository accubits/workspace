import { Component, OnInit, EventEmitter, Output,QueryList,ViewChildren , ElementRef, ViewChild} from '@angular/core';
import { DataService } from '../../shared/services/data.service';
import { UtilityService } from '../../shared/services/utility.service';
import { FormsUtilityService } from '../../shared/services/forms-utility.service';
import { ScrollToService, ScrollToConfigOptions } from '@nicky-lenaers/ngx-scroll-to';


@Component({
  selector: 'app-content-wrap-right',
  templateUrl: './content-wrap-right.component.html',
  styleUrls: ['./content-wrap-right.component.scss']
})
export class ContentWrapRightComponent implements OnInit {
  @ViewChild('scrollMe') private myScrollContainer: ElementRef;

  constructor(
    public dataService: DataService,
    private utilityService: UtilityService,
    public formsUtilityService: FormsUtilityService,
    private _scrollToService: ScrollToService
  ) {}

  pushElement(id) {
     this.dataService.formElementArray.push({
      index: this.utilityService.generaterandomID(),
      name: id,
      id:'1',
      type: 'item',
      element:{},
      pagination:{currentPage:0}
    });
    setTimeout(()=>{
      this.scrollToBottom();
    },150);
    // console.log(this.dataService.formElementArray);
   // this.dataService.formElementToggle.activeIndex = this.dataService.formElementArray.length  -1;
  }

  deleteElement(id) {
    console.log(id)
    for(var item in this.dataService.formElementArray) {
      console.log(this.dataService.formElementArray[item],id);
      if(this.dataService.formElementArray[item]['index']==id){
       // this.dataService.formElementArray.splice(item,1)
       // this.updatePreview.emit(this.dataService.formElementArray)
      }
    }
  }
  
  ngOnInit() {
    this.formsUtilityService.invalidElement.subscribe(index => {
      const config: ScrollToConfigOptions = {
        target: index
      };
      this._scrollToService.scrollTo(config);
    })
    
  }
 
  public removeItem(item: any, list: any[]): void {
    var nw = JSON.stringify(list);
    console.log(nw);
    list.splice(list.indexOf(item), 1);
  }
 
  /* Event call when a new element is inserted */
  inserted(draggedelement){
     console.log(draggedelement)
     if(!this.dataService.formElementArray[draggedelement.index].index){
      this.dataService.formElementArray[draggedelement.index].index = this.utilityService.generaterandomID();
    }

    // setTimeout(()=>{
    //   this.scrollToBottom();
    // },100);

  }

  scrollToBottom(): void {
    console.log(this.myScrollContainer);
    try {
        console.log(this.myScrollContainer.nativeElement.scrollHeight);
        this.myScrollContainer.nativeElement.scrollTop = this.myScrollContainer.nativeElement.scrollHeight;
   
    console.log(this.myScrollContainer);
      } catch(err) {
      }  
      
      
}


}


