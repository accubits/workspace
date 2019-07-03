import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-multiple-choice-preview',
  templateUrl: './multiple-choice-preview.component.html',
  styleUrls: ['./multiple-choice-preview.component.scss']
})
export class MultipleChoicePreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* Data Model for multiple choice element */
  multipleChoiceElement = {
    componentId: null,
    action: 'create',
    type: 'multipleChoice',
    multipleChoice: {
      label: 'select a choice',
      choices: [
        {
          optId: null,
          action: 'create',
          label: 'choice 1',
          maxQuantity: ''
        },
        {
          optId: null,
          action: 'create',
          label: 'choice 2',
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
      this.multipleChoiceElement = this.currentElement['element']
    }, 100);
  }

}
