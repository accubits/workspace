import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';
import { FormsApiService } from '../../../shared/services/forms-api.service';


@Component({
  selector: 'app-address-submit',
  templateUrl: './address-submit.component.html',
  styleUrls: ['./address-submit.component.scss']
})
export class AddressSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  countryPopup: boolean = false;
  countryNames: '';
  constructor(
    public dataService: DataService,
    public formsApiService: FormsApiService


  ) { }

  /* Data model for address element */
  addressElement = {
    componentId: null,
    type: 'address',
    address: {
      label: '',
      isRequired: true,
      streetAddress: '',
      addressLine2: '',
      city: '',
      state: '',
      countryId: null,
      zipCode: ''
    },
    elementToSubmit: {},
    isValidated: true,
    isValidFormat:true


  }

  /* Data model for address submit */
  addressSubmit: any = {
    componentId: null,
    type: 'address',
    address: {
      streetAddress: '',
      addressLine2: '',
      city: '',
      state: '',
      countryName: '',
      countryId: null,
      zipCode: ''
    }
  }

  ngOnInit() {
    this.formsApiService.getAllCountries().subscribe((result: any) => {
      
      this.dataService.countryTemplate.countries = result.data.countries;
      if(this.addressElement.address.streetAddress){
      let selCountry = this.dataService.countryTemplate.countries.filter(
        cnt => cnt.id === this.addressElement.address.countryId)[0];
      this.addressSubmit.address.countryName =  selCountry.name;
      this.countryNames = this.addressSubmit.address.countryName;
      }
    },
      err => {
        console.log(err);

      })
    setTimeout(() => {
      this.addressElement = this.data;
      this.addressSubmit.componentId = this.addressElement.componentId;
      if (!this.addressElement.hasOwnProperty('elementToSubmit')) {
        if(this.addressElement.address.streetAddress){
          this.addressSubmit.address.streetAddress =  this.addressElement.address.streetAddress;
          this.addressSubmit.address.addressLine2 =  this.addressElement.address.addressLine2;
          this.addressSubmit.address.city =  this.addressElement.address.city;
          this.addressSubmit.address.state =  this.addressElement.address.state;
          this.addressSubmit.address.zipCode =  this.addressElement.address.zipCode;
          this.addressSubmit.address.countryId =  this.addressElement.address.countryId;
          let selCountry = this.dataService.countryTemplate.countries.filter(
            cnt => cnt.id === this.addressElement.address.countryId)[0];
          this.addressSubmit.address.countryName =  selCountry.name;
          this.countryNames = this.addressSubmit.address.countryName;
          
        }
        this.addressElement.elementToSubmit = this.addressSubmit;
      } else {
        this.addressSubmit = this.addressElement.elementToSubmit;
        this.countryNames = this.addressSubmit.address.countryName;
      }
      this.validateElement()

    }, 200)

  }
  /* Entering the selected countries for submit */

  countryList(country) {
    this.addressSubmit.address.countryId = country.id;
    this.countryNames = country.name;
    this.addressSubmit.address.countryName = country.name;
    this.countryPopup = false;
    this.validateElement()

  }

  /* Validating Element[Start] */
  validateElement(): void {
    this.addressElement.address.isRequired && (!this.addressSubmit.address.streetAddress || !this.addressSubmit.address.addressLine2 || !this.addressSubmit.address.city || !this.addressSubmit.address.state || !this.countryNames || !this.addressSubmit.address.zipCode) ?
      this.addressElement.isValidated = false : this.addressElement.isValidated = true;
      this.addressElement.isValidated? this.addressElement.isValidFormat = true:this.addressElement.isValidFormat = false;

  }
  /* Validating Element[End] */

}
