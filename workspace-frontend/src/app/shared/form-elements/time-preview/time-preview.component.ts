import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-time-preview',
  templateUrl: './time-preview.component.html',
  styleUrls: ['./time-preview.component.scss']
})
export class TimePreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;
  fp_ampm:boolean=false;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
 
  /* Data model for time element */
  timeElement =   {
    componentId: null,
    action: 'create',
    type: 'time',
    time: {
      label: '',
      isRequired: false,
      noDuplicate: false
    }
  }


 currentElement:{}

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      this.timeElement = this.currentElement['element']
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }

 
}
