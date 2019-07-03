import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';
import * as _moment from 'moment';
import { UtilityService } from '../../../shared/services/utility.service';

@Component({
  selector: 'app-date-submit',
  templateUrl: './date-submit.component.html',
  styleUrls: ['./date-submit.component.scss']
})
export class DateSubmitComponent implements OnInit {
  @Input() data: any; 
  day;
  month;
  year;
  yearString;
  dateObj;
  datearr: any;

 public assetUrl = Configs.assetBaseUrl;
  constructor(
    public dataService: DataService,
    public utilityService: UtilityService) {}

   dateStringSubmit()  {
    if(this.day && this.month && this.year){
     // this.dateObj = new Date(this.day+'-'+this.month+'-'+this.year).getTime()/1000;
      this.dateSubmit.date.answer = new Date(this.month+'-'+this.day+'-'+this.year).getTime()/1000;  //this.utilityService.convertToUnix(this.dateObj);
    
    }
   }

  /* Data model for date element */

  dateElement = {
    type: 'date',
    componentId: null,
    date: {
      isRequired: true,
      noDuplicate: false,
      label: '',
      answer:''
    }, 
    elementToSubmit:{},
    isValidated:true,
    isValidFormat:true

  } 
    /* Data model for date submit */

dateSubmit: any = {
  componentId: null,
  type: 'date',
  date: {
    dateString:'',
    answer:''
    }
  }

  ngOnInit() {
    // setTimeout(() => {
      this.dateElement = this.data;
      this.dateSubmit.componentId = this.dateElement.componentId;
      if(!this.dateElement.hasOwnProperty('elementToSubmit')){
        if(this.dateElement.date.answer){
          this.dateSubmit.date.answer =  this.dateElement.date.answer;
          this.month = new Date(this.dateSubmit.date.answer*1000).getMonth() + 1;
          this.day = new Date(this.dateSubmit.date.answer*1000).getDate();
          this.year = new Date(this.dateSubmit.date.answer*1000).getFullYear();
       }
        this.dateElement.elementToSubmit = this.dateSubmit; 
        this.dateElement.date.isRequired && (!this.day ||!this.month ||!this.year)?
        this.dateElement.isValidated =  false:this.dateElement.isValidated =  true;
        this.dateElement.isValidated? this.dateElement.isValidFormat = true:this.dateElement.isValidFormat = false;   
     }else{
      this.dateSubmit = this.dateElement.elementToSubmit;
      this.month = new Date(this.dateSubmit *1000).getMonth() + 1;
          this.day = new Date(this.dateSubmit * 1000).getDate();
          this.year = new Date(this.dateSubmit *1000).getFullYear();
          this.dateElement.date.isRequired && (!this.day ||!this.month ||!this.year)?
          this.dateElement.isValidated =  false:this.dateElement.isValidated =  true;
          this.dateElement.isValidated? this.dateElement.isValidFormat = true:this.dateElement.isValidFormat = false;
     }
    // }, 100)
  }

  /* Validating Element[Start] */

  validateElement():void{
    this.dateElement.date.isRequired && (!this.day ||!this.month ||!this.year)?
    this.dateElement.isValidated =  false:this.dateElement.isValidated =  true;
    this.dateElement.isValidated? this.dateElement.isValidFormat = true:this.dateElement.isValidFormat = false;
    if(this.dateElement.isValidated){
      if(!this.day ||!this.month ||!this.year){
        this.dateSubmit.date.answer =  null;
        return;
      }
      if (!_moment(this.year+'-'+this.month+'-'+this.day).isValid())
      {
        this.dataService.showDate.show = true;
      }else{
        this.dataService.showDate.show = false;
      }
      this.dateSubmit.date.dateString = this.day+'-'+this.month+'-'+this.year;
      let yearString = new String(this.year) 
      yearString.length !== 4 ?
      this.dateElement.isValidFormat = false : this.dateElement.isValidFormat = true;
      let con = this.month+'-'+this.day+'-'+this.year;
      this.dateSubmit.date.answer = new Date(con).getTime()/1000;
      this.dateStringSubmit();
    }
  }
}