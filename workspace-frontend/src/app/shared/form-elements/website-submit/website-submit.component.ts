import { Component, OnInit, Input  } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';
import { UtilityService } from '../../../shared/services/utility.service';


@Component({
  selector: 'app-website-submit',
  templateUrl: './website-submit.component.html',
  styleUrls: ['./website-submit.component.scss']
})
export class WebsiteSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(
    public dataService: DataService,
    public utilityService: UtilityService,
  ) { }

    /* Data model for website element */

    websiteElement = {
      type: 'website',
      componentId: null,
      website: {
        isRequired: true,
        noDuplicate: false,
        label: '',
        answer: ''
        
      },
      elementToSubmit:{},
      isValidated:true,
      isValidFormat:true


    }

      /* Data model for website submit */

      websiteSubmit:any = {
        componentId: null,
        type: 'website',
        website: {
        answer: ''
        }
     }
    
  ngOnInit() {
    setTimeout(() => {
      this.websiteElement = this.data;
      this.websiteSubmit.componentId = this.websiteElement.componentId;
      if(!this.websiteElement.hasOwnProperty('elementToSubmit')){
        if(this.websiteElement.website.answer){
          this.websiteSubmit.website.answer =  this.websiteElement.website.answer;
        }
        this.websiteElement.elementToSubmit = this.websiteSubmit;
     }else{
      this.websiteSubmit = this.websiteElement.elementToSubmit;
     }
      this.validateElement()
    }, 100)
  }

   /* Validating Element[Start] */
   validateElement():void{
    this.websiteElement.website.isRequired && !this.websiteSubmit.website.answer?
    this.websiteElement.isValidated =  false:this.websiteElement.isValidated =  true; 
    if(this.websiteElement.isValidated && this.websiteSubmit.website.answer){
      this.checkValidFormat();
    }else{
      this.websiteElement.isValidFormat = true
    }

   }
   /* Validating Element[End] */

     /* Checking website is invalid format */
     checkValidFormat():void{
      this.utilityService.validateWebsite(this.websiteSubmit.website.answer)?
        this.websiteElement.isValidFormat = true : this.websiteElement.isValidFormat = false;
   }


}
