import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-phone-preview',
  templateUrl: './phone-preview.component.html',
  styleUrls: ['./phone-preview.component.scss']
})
export class PhonePreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
 
   /* Data model for phone element */
   phoneElement = {
    componentId: null,
    action: 'create',
    type: 'phone',
    phone: {
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
      this.phoneElement = this.currentElement['element']
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }

}
