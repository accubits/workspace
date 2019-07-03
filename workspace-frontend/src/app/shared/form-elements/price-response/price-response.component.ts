import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-price-response',
  templateUrl: './price-response.component.html',
  styleUrls: ['./price-response.component.scss']
})
export class PriceResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for price*/
  priceElement = {
    "type": "price",
    "componentId": null,
    "price": {
      "isRequired": false,
      "label": "",
      "currency": null,
      "currencyUnit": null
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.priceElement = this.data;
   }, 100)
  }

}
