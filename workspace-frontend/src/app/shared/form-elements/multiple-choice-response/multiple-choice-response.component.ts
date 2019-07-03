import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-multiple-choice-response',
  templateUrl: './multiple-choice-response.component.html',
  styleUrls: ['./multiple-choice-response.component.scss']
})
export class MultipleChoiceResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for single line text */
  multipleChoiceElement =  {
    "type": "multipleChoice",
    "componentId": null,
    "multipleChoice": {
      "label": "",
      "isRequired": false,
      "choices": [
        {
          "optId": null,
          "fqoSortNo": null,
          "label": "",
          "maxQuantity": null,
          "alreadySelectedCount": null,
          "selected": false
        },
       
      ]
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.multipleChoiceElement = this.data;
   }, 100)
  }


}
