import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-time-response',
  templateUrl: './time-response.component.html',
  styleUrls: ['./time-response.component.scss']
})
export class TimeResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for time*/
  timeElement =  {
    "type": "time",
    "componentId": null,
    "time": {
      "isRequired": false,
      "noDuplicate": false,
      "label": "",
      "answer": ""
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.timeElement = this.data;
   }, 100)
  }


}
