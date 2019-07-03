import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-checkboxes-response',
  templateUrl: './checkboxes-response.component.html',
  styleUrls: ['./checkboxes-response.component.scss']
})
export class CheckboxesResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for single line text */
  checkBoxElement =  {
    "type": "checkboxes",
    "componentId": null,
    "checkboxes": {
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
      this.checkBoxElement = this.data;
   }, 100)
  }

}
