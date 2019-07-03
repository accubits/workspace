import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-paragraph-response',
  templateUrl: './paragraph-response.component.html',
  styleUrls: ['./paragraph-response.component.scss']
})
export class ParagraphResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for single line text */
  paragraphElement =       {
    "type": "paragraphText",
    "componentId": null,
    "paragraphText": {
      "isRequired": false,
      "noDuplicate": false,
      "label": "",
      "answer": ""
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.paragraphElement = this.data;
   }, 100)
  }
}
