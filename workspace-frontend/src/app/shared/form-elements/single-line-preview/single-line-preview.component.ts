import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-single-line-preview',
  templateUrl: './single-line-preview.component.html',
  styleUrls: ['./single-line-preview.component.scss']
})
export class SingleLinePreviewComponent implements OnInit {
  @Input() data: any;

  public assetUrl = Configs.assetBaseUrl;
  currentElementIndex: string;
  currentElement: {};

   /* Data model for single line text */
   singleLineTextElement={
    componentId: null,
    action: 'create',
    type: 'singleLineText',
    singleLineText: {
      label: '',
      isRequired: false,
      noDuplicate: false
    }
  }

  constructor(public dataService: DataService) { }

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
        this.singleLineTextElement = this.currentElement['element'];
    }, 100)
  }

 

}
