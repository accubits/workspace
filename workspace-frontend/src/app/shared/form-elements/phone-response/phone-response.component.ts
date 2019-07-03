import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-phone-response',
  templateUrl: './phone-response.component.html',
  styleUrls: ['./phone-response.component.scss']
})
export class PhoneResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for phone*/
  phoneElement = {
    "type": "phone",
    "componentId": null,
    "phone": {
      "isRequired": false,
      "noDuplicate": false,
      "label": "",
      "answer": ""
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.phoneElement = this.data;
   }, 100)
  }

}
