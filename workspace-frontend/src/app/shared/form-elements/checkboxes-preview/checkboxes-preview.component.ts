import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-checkboxes-preview',
  templateUrl: './checkboxes-preview.component.html',
  styleUrls: ['./checkboxes-preview.component.scss']
})
export class CheckboxesPreviewComponent implements OnInit {

  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;

   /* Data model for checkbox element */
   checkboxElement= {
    componentId: null,
    action: 'create',
    type: 'checkboxes',
    checkboxes: {
      label: '',
      choices: [
        {
          optId: null,
          action: 'create',
          label: '',
          maxQuantity: 0
        },
        {
          optId: null,
          action: 'create',
          label: '',
          maxQuantity: 0
        }
      ],
      isRequired: true
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
      this.checkboxElement = this.currentElement['element']
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }
}
