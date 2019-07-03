import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-date-response',
  templateUrl: './date-response.component.html',
  styleUrls: ['./date-response.component.scss']
})
export class DateResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for date*/
  dateElement =   {
    "type": "date",
    "componentId": null,
    "date": {
      "isRequired": false,
      "noDuplicate": false,
      "label": "",
      "answer": null
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.dateElement = this.data;
   }, 100)
  }

}
