import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-number-preview',
  templateUrl: './number-preview.component.html',
  styleUrls: ['./number-preview.component.scss']
})
export class NumberPreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;

   /* Data model for number element */
   numberElement={
    type: 'number',
    componentId: null,
    action: 'create',
    number: {
      isRequired: false,
      noDuplicate: false,
      minRange: '',
      maxRange: '',
      label: 'Number'
    }
  }
  currentElement: {}


  constructor(
    public dataService: DataService
  ) { }

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      this.numberElement = this.currentElement['element']
      // console.log(this.dataService.formElementToggle.activeIndex);
      // console.log(this.currentElementIndex);
    }, 100);
  }

}
