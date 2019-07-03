import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-dropdown-response',
  templateUrl: './dropdown-response.component.html',
  styleUrls: ['./dropdown-response.component.scss']
})
export class DropdownResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for single line text */
  dropDownElement =  {
    "type": "dropdown",
    "componentId": null,
    "dropdown": {
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
        }
      ]
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.dropDownElement = this.data;
   }, 100)
  }


}
