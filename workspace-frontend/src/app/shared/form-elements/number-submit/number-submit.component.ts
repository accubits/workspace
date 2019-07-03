import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-number-submit',
  templateUrl: './number-submit.component.html',
  styleUrls: ['./number-submit.component.scss']
})
export class NumberSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* Data model for number element */
  numberElement = {
    type: 'number',
    componentId: null,
    action: 'create',
    number: {
      isRequired: false,
      noDuplicate: false,
      minRange: '',
      maxRange: '',
      label: '',
      answer:'',
    },
    elementToSubmit: {},
    isValidated:true,
    isValidFormat:true

  }

  numberSubmit : any= {
    componentId: null,
    type: 'number',
    number: {
      answer: ''
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.numberElement = this.data;
      this.numberSubmit.componentId = this.numberElement.componentId;
      if(!this.numberElement.hasOwnProperty('elementToSubmit')){
        if(this.numberElement.number.answer){
          this.numberSubmit.number.answer =  this.numberElement.number.answer;
        }
        this.numberElement.elementToSubmit = this.numberSubmit;

     }else{
      this.numberSubmit = this.numberElement.elementToSubmit;
     }
      
      this.validateElement()
    }, 100)
    
  }

   /* Validating Element[Start] */
   validateElement():void{
    this.numberElement.number.isRequired && !this.numberSubmit.number.answer?
       this.numberElement.isValidated =  false:this.numberElement.isValidated =  true;
       this.numberElement.isValidated? this.numberElement.isValidFormat = true:this.numberElement.isValidFormat = false;

    
   }
   /* Validating Element[End] */
 

}
