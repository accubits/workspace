import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-number-response',
  templateUrl: './number-response.component.html',
  styleUrls: ['./number-response.component.scss']
})
export class NumberResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for single line text */
  numberElement = {
    "type": "number",
    "componentId": null,
    "number": {
      "isRequired": false,
      "noDuplicate": false,
      "minRange": null,
      "maxRange": null,
      "label": "",
      "answer": null
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.numberElement = this.data;
   }, 100)
  }
}
