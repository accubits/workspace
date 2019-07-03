import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';
import { UtilityService } from '../../../shared/services/utility.service';

@Component({
  selector: 'app-phone-submit',
  templateUrl: './phone-submit.component.html',
  styleUrls: ['./phone-submit.component.scss']
})
export class PhoneSubmitComponent implements OnInit {
  @Input() data: any;

  public assetUrl = Configs.assetBaseUrl;
  constructor(
    public dataService: DataService,
    public utilityService: UtilityService,
    ) { }

  /* Data model for phone element */

  phoneElement = {
    type: 'phone',
    componentId: null,
    phone: {
      isRequired: false,
      noDuplicate: false,
      label: '',
      answer: ''
    },
    elementToSubmit:{},
    isValidated:true,
    isValidFormat:true

  } 

    /* Data model for phone submit */

    phoneSubmit: any = {
      componentId: null,
      type: 'phone',
      phone: {
      answer: ''
      }
    }


  ngOnInit() {
    setTimeout(() => {
      this.phoneElement = this.data;
      this.phoneSubmit.componentId = this.phoneElement.componentId;
      if(!this.phoneElement.hasOwnProperty('elementToSubmit')){
        if(this.phoneElement.phone.answer){
          this.phoneSubmit.phone.answer =  this.phoneElement.phone.answer;
        }
        this.phoneElement.elementToSubmit = this.phoneSubmit;
     }else{
      this.phoneSubmit = this.phoneElement.elementToSubmit;
     }   
    this.validateElement();
    }, 100)
  }

  /* Validating Element[Start] */
  validateElement():void{
    this.phoneElement.phone.isRequired && !this.phoneSubmit.phone.answer?
    this.phoneElement.isValidated =  false:this.phoneElement.isValidated =  true; 
    if(this.phoneElement.isValidated && this.phoneSubmit.phone.answer){
      this.checkValidFormat();
    }else{
      this.phoneElement.isValidFormat = true
    }
   }
   /* Validating Element[End] */

   /* Checking phone number is in valid format */
   checkValidFormat():void{
      // this.utilityService.validatePhone(this.phoneSubmit.phone.answer)?
      //   this.phoneElement.isValidFormat = true : this.phoneElement.isValidFormat = false;
      this.phoneSubmit.phone.answer.length > 5 && this.phoneSubmit.phone.answer.length < 15?
      this.phoneElement.isValidFormat = true : this.phoneElement.isValidFormat = false;
   }

}
