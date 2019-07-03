import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-dropdown-preview',
  templateUrl: './dropdown-preview.component.html',
  styleUrls: ['./dropdown-preview.component.scss']
})
export class DropdownPreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;
  showChoice:boolean=false;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  dropdownElement = {
    componentId: null,
    action: 'create',
    type: 'dropdown',
    dropdown: {
      label: '',
      choices: [
        {
          optId: null,
          action: 'create',
          label: '',
          maxQuantity: ''
        },
        {
          optId: null,
          action: 'create',
          label: '',
          maxQuantity: ''
        }
      ],
      isRequired: false,
      randomize: false,
      allowOther: false,
      otherLabel: ''
    }
  }

  currentElement:{}

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      this.dropdownElement = this.currentElement['element']
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }
}
