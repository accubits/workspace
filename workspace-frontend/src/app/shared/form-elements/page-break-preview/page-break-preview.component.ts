import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-page-break-preview',
  templateUrl: './page-break-preview.component.html',
  styleUrls: ['./page-break-preview.component.scss']
})
export class PageBreakPreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) {

   }

   /* Data model for number element */
 /* Data model for number element */
 pageElement={
  componentId: null,
  action: "create",
  type: "page",
  page: {
    title: "page",
    description: ""
 }
}

 currentElement:any= { pagination:{currentPage:0}};
 
 
  ngOnInit() {
  

    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
        if( Object.getOwnPropertyNames(this.currentElement['element']).length === 0){
          this.currentElement['element'] = this.pageElement;
         }else{
          this.pageElement = this.currentElement['element'];
         }
  
         let totalPages = this.dataService.formElementArray.filter(
          element => element.name === 'page');
  
          for(let i=0;i<totalPages.length;i++){
            totalPages[i].pagination.currentPage = i + 1;
  
          }
          this.dataService.formPages.total = totalPages.length; 
        
             
      this.pageElement = this.currentElement['element']
     
    }, 300);
  }
  
}
