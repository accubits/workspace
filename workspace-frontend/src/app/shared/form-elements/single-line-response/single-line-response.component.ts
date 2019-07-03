import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-single-line-response',
  templateUrl: './single-line-response.component.html',
  styleUrls: ['./single-line-response.component.scss']
})
export class SingleLineResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for single line text */
  singleLineElement =  {
    "type": "singleLineText",
    "componentId": null,
    "singleLineText": {
      "isRequired": true,
      "noDuplicate": false,
      "label": "",
      "answer": ""
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.singleLineElement = this.data;
   }, 100)
  }
}
