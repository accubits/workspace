import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';
import { FormsApiService} from '../../../shared/services/forms-api.service';
import { element } from 'protractor';


@Component({
  selector: 'app-address-response',
  templateUrl: './address-response.component.html',
  styleUrls: ['./address-response.component.scss']
})
export class AddressResponseComponent implements OnInit {
  @Input() data: any;
  noAnswerd :boolean = false;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService,
    public formsApiService: FormsApiService
  ) { }
  countryElement:any= {};


  /* data model for address*/
  addressElement = {
    "type": "address",
    "componentId": null,
    "address": {
      "isRequired": false,
      "noDuplicate": false,
      "label": "",
      "streetAddress": "",
      "addressLine2": "",
      "city": "",
      "state": "",
      "countryId": null,
      "zipCode": ""
    }
  }

  ngOnInit() { 
    if(this.data.address.addressLine2 === '' && this.data.address.city === '' && this.data.address.countryId === null
    && this.data.address.state === '' && this.data.address.streetAddress === '' && this.data.address.zipCode === '') {
    this.noAnswerd = true;
    }
   setTimeout(() => {
      this.addressElement = this.data;
      this.countryElement = this.dataService.countryTemplate.countries.filter(
        country => country.id === this.addressElement.address.countryId)[0]
        if(!this.countryElement)this.countryElement = {};
   }, 100)
  }

}
