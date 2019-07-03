import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-email-response',
  templateUrl: './email-response.component.html',
  styleUrls: ['./email-response.component.scss']
})
export class EmailResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for time*/
  emailElement =  {
    "type": "email",
    "componentId": null,
    "email": {
      "isRequired": false,
      "noDuplicate": false,
      "label": "",
      "answer": ""
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.emailElement = this.data;
   }, 100)
  }

}
