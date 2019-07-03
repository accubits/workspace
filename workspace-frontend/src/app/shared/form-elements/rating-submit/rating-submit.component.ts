import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-rating-submit',
  templateUrl: './rating-submit.component.html',
  styleUrls: ['./rating-submit.component.scss']
})
export class RatingSubmitComponent implements OnInit {
  @Input() data: any;
  rating: number 
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  setRating(selRating: number) {
     this.rating = selRating;
     this.ratingSubmit.rating.answer = this.rating.toString();
     this.validateElement();
  }
  /* Data model for rating element */
  ratingElement = {
    type: 'rating',
    componentId: null,
    rating: {
      isRequired: true,
      noDuplicate: false,
      label: '',
      answer: ''
    },
    elementToSubmit: {},
    isValidated:true,
    isValidFormat:true
  }
  /* Data model for rating submit */

  ratingSubmit :any = {
    componentId: null,
    type: 'rating',
    rating: {
      answer: ''
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.ratingElement = this.data;
      this.ratingSubmit.componentId = this.ratingElement.componentId;
      if(!this.ratingElement.hasOwnProperty('elementToSubmit')){
        if(this.ratingElement.rating.answer){
          this.ratingSubmit.rating.answer =  this.ratingElement.rating.answer;
          this.rating = parseInt(this.ratingSubmit.rating.answer);
        }
        this.ratingElement.elementToSubmit = this.ratingSubmit;
     }else{
      this.ratingSubmit = this.ratingElement.elementToSubmit;
      this.rating = parseInt(this.ratingSubmit.rating.answer);
     }
      this.validateElement();
    }, 100)
  }
   /* Validating Element[Start] */
   validateElement():void{
    this.ratingElement.rating.isRequired && (this.ratingSubmit.rating.answer === '0'
    || !this.ratingSubmit.rating.answer)?
    this.ratingElement.isValidated =  false:this.ratingElement.isValidated =  true;
    this.ratingElement.isValidated? this.ratingElement.isValidFormat = true:this.ratingElement.isValidFormat = false;
  
  }
   /* Validating Element[End] */

   resetRating(){
     this.setRating(0);
     this.ratingSubmit.rating.answer = "";
   }
}