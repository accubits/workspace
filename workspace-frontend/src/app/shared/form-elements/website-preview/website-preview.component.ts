import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-website-preview',
  templateUrl: './website-preview.component.html',
  styleUrls: ['./website-preview.component.scss']
})
export class WebsitePreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
 
   /* Data model for website element */
   websiteElement = {
    componentId: null,
    action: 'create',
    type: 'website',
    website: {
      label: '',
      isRequired: true,
      noDuplicate: false
    }
  }


 currentElement:{}

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      this.websiteElement = this.currentElement['element']
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }


}
