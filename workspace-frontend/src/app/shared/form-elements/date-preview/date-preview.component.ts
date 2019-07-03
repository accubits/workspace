import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-date-preview',
  templateUrl: './date-preview.component.html',
  styleUrls: ['./date-preview.component.scss']
})
export class DatePreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
 
  /* Data model for date element */
  dateElement = {
    componentId: null,
    action: 'create',
    type: 'date',
    date: {
      label: 'Date',
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
      this.dateElement = this.currentElement['element']
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }


}
