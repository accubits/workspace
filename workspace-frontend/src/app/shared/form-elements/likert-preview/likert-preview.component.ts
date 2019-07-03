import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-likert-preview',
  templateUrl: './likert-preview.component.html',
  styleUrls: ['./likert-preview.component.scss']
})
export class LikertPreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
 
  /* Data model for likert element */
  likertElement = {
    componentId: null,
    action: 'create',
    type: 'likert',
    likert: {
      label: '',
      statements: [
        {
          stmtId: null,
          action: 'create',
          stmt: ''
        },
        {
          stmtId: null,
          action: 'create',
          stmt: ''
        },
        {
          stmtId: null,
          action: 'create',
          stmt: ''
        }
      ],
      columns: [
        {
          colId: null,
          action: 'create',
          column: ''
        },
        {
          colId: null,
          action: 'create',
          column: ''
        },
        {
          colId: null,
          action: 'create',
          column: ''
        },
      ],
      isRequired: true
    }
  }

 currentElement:{}

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      this.likertElement = this.currentElement['element']
    }, 100);
  }

}
