import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-single-line-submit',
  templateUrl: './single-line-submit.component.html',
  styleUrls: ['./single-line-submit.component.scss']
})
export class SingleLineSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for single line text */
  singleLineElement = {
    type: 'singleLineText',
    componentId: null,
    singleLineText: {
      isRequired: true,
      noDuplicate: true,
      label: '',
      answer:''
    },
    elementToSubmit: {},
    isValidated:true,
    isValidFormat:true

  }

  singleLineSubmit : any = {
    componentId: null,
    type: 'singleLineText',
    singleLineText: {
      answer: ''
    }
  }

  ngOnInit() {
    console.log('test', this.data.singleLineText.isRequired);
    setTimeout(() => {
      this.singleLineElement = this.data;
      this.singleLineSubmit.componentId = this.singleLineElement.componentId;
      if(!this.singleLineElement.hasOwnProperty('elementToSubmit')){
        if(this.singleLineElement.singleLineText.answer){
          this.singleLineSubmit.singleLineText.answer =  this.singleLineElement.singleLineText.answer;
        }
        this.singleLineElement.elementToSubmit = this.singleLineSubmit;
       
     }else{
      this.singleLineSubmit = this.singleLineElement.elementToSubmit;
     }

   
      
      this.validateElement()
    }, 100)
  }

  /* Validating Element[Start] */
  validateElement():void{
   this.singleLineElement.singleLineText.isRequired && !this.singleLineSubmit.singleLineText.answer?
      this.singleLineElement.isValidated =  false:this.singleLineElement.isValidated =  true;
      
      this.singleLineElement.isValidated? this.singleLineElement.isValidFormat = true:this.singleLineElement.isValidFormat = false;
  }
  /* Validating Element[End] */

}
