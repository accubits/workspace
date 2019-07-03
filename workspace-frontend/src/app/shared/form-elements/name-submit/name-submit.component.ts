import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-name-submit',
  templateUrl: './name-submit.component.html',
  styleUrls: ['./name-submit.component.scss']
})
export class NameSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
 
  /* Data model for name element */
  nameElement = {
    componentId: null,
    type: 'name',
    name: {
      label: '',  
      isRequired: false,
      noDuplicate: false,
      first: "",
      last: ""
    },
    elementToSubmit:{},
    isValidated:true,
    isValidFormat:true


  }
 

  /* Data model for name submit */
  nameSubmit: any = {
    componentId: null,
    type: 'name',
    name: {
      first: '',
      last: ''
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.nameElement = this.data;
      this.nameSubmit.componentId = this.nameElement.componentId;
      if(!this.nameElement.hasOwnProperty('elementToSubmit')){
        if(this.nameElement.name.first){
          this.nameSubmit.name.first =  this.nameElement.name.first;
          this.nameSubmit.name.last =  this.nameElement.name.last;
        }
        this.nameElement.elementToSubmit = this.nameSubmit;
     }else{
      this.nameSubmit = this.nameElement.elementToSubmit;
     }  
         this.validateElement()
    }, 100)

  }
   /* Validating Element[Start] */
   validateElement():void{
    this.nameElement.name.isRequired && (!this.nameSubmit.name.first || !this.nameSubmit.name.last)?
    this.nameElement.isValidated =  false:this.nameElement.isValidated =  true;
    this.nameElement.isValidated? this.nameElement.isValidFormat = true:this.nameElement.isValidFormat = false;

    
   }
   /* Validating Element[End] */ 
 

}
