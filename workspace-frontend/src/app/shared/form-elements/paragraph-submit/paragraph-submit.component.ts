import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-paragraph-submit',
  templateUrl: './paragraph-submit.component.html',
  styleUrls: ['./paragraph-submit.component.scss']
})
export class ParagraphSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

 

   /* Data model for paragraph element */
   paragraphElement =   {
    componentId: null,
    action: "create",
    type: "paragraphText",
    paragraphText: {
      label: "",
      isRequired: false,
      noDuplicate: false,
      answer:''
    },
    elementToSubmit:{},
    isValidated:true,
    isValidFormat:true


  }

  /* Data model for paragraphh submit */
  paragraphSubmit:any = {
    componentId: 5,
    type: 'paragraphText',
    paragraphText: {
    answer: ''
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.paragraphElement = this.data;
      this.paragraphSubmit.componentId = this.paragraphElement.componentId;
      if(!this.paragraphElement.hasOwnProperty('elementToSubmit')){
        if(this.paragraphElement.paragraphText.answer){
          this.paragraphSubmit.paragraphText.answer =  this.paragraphElement.paragraphText.answer;
        }
        this.paragraphElement.elementToSubmit = this.paragraphSubmit;
     }else{
      this.paragraphSubmit = this.paragraphElement.elementToSubmit;
     }
     this.validateElement();
    }, 100)
  }

  /* Validating Element[Start] */
  validateElement():void{
    this.paragraphElement.paragraphText.isRequired && !this.paragraphSubmit.paragraphText.answer?
       this.paragraphElement.isValidated =  false:this.paragraphElement.isValidated =  true;
       this.paragraphElement.isValidated? this.paragraphElement.isValidFormat = true:this.paragraphElement.isValidFormat = false;

   }
   /* Validating Element[End] */

}
