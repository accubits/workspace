import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-website-response',
  templateUrl: './website-response.component.html',
  styleUrls: ['./website-response.component.scss']
})
export class WebsiteResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for time*/
  websiteElement = {
    "type": "website",
    "componentId": null,
    "website": {
      "isRequired": false,
      "noDuplicate": false,
      "label": "",
      "answer": ""
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.websiteElement = this.data;
   }, 100)
  }

}
