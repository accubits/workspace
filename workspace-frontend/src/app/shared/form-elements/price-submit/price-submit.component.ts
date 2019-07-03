import { Component, OnInit , Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-price-submit',
  templateUrl: './price-submit.component.html',
  styleUrls: ['./price-submit.component.scss']
})
export class PriceSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* Data model for price element */

  priceElement = {
    componentId: null,
    type: 'price',
    price: {
      label: '',
      isRequired: true,
      currencyType: ''
    },
    elementToSubmit:{},
    isValidated:true,
    isValidFormat:true


  }

    /* Data model for price submit */

    priceSubmit : any = {
      componentId: null,
      type: 'price',
      price: {
        currency: '',
        currencyUnit: ''
        }
      }

  ngOnInit() {
    setTimeout(() => {
      this.priceElement = this.data;
      this.priceSubmit.componentId = this.priceElement.componentId;
      if(!this.priceElement.hasOwnProperty('elementToSubmit')){
        this.priceElement.elementToSubmit = this.priceSubmit;
     }else{
      this.priceSubmit = this.priceElement.elementToSubmit;
     }
     this.validateElement();
    }, 100)
  }

  /* Validating Element[Start] */
  validateElement():void{
    this.priceElement.price.isRequired && (!this.priceSubmit.price.currency ||  !this.priceSubmit.price.currencyUnit)?
    this.priceElement.isValidated =  false:this.priceElement.isValidated =  true;
    this.priceElement.isValidated? this.priceElement.isValidFormat = true:this.priceElement.isValidFormat = false;
 
   }
   /* Validating Element[End] */
 

}
