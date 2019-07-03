import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-name-response',
  templateUrl: './name-response.component.html',
  styleUrls: ['./name-response.component.scss']
})
export class NameResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
  noAnswerd :boolean = false;

  /* data model for single line text */
  nameElement = {
    "type": "name",
    "componentId": null,
    "name": {
      "isRequired": false,
      "noDuplicate": false,
      "label": "",
      "first": "",
      "last": ""
    }
  }

  ngOnInit() {
    if(this.data.name.first === null && this.data.name.last === null) {
    this.noAnswerd = true;
    }
    setTimeout(() => {
      this.nameElement = this.data;
   }, 100)
  }
}
