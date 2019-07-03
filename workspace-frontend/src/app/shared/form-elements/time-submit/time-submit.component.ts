import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';
import { UtilityService } from '../../../shared/services/utility.service';


@Component({
  selector: 'app-time-submit',
  templateUrl: './time-submit.component.html',
  styleUrls: ['./time-submit.component.scss']
})
export class TimeSubmitComponent implements OnInit {
  @Input() data: any;
  hours;
  minutes;
  seconds;
  timeDivision;
  timeString;
  timeObj;
  timearr: any

  public assetUrl = Configs.assetBaseUrl;
  constructor(

    public dataService: DataService,
    public utilityService: UtilityService,
  ) { }
  fp_ampm = false;


  timeStringSubmit() {
    this.timeString = this.hours + ':' + this.minutes + ':' + this.seconds;

    if (this.hours && this.minutes && this.seconds) {
      this.timeSubmit.time.answer = this.timeString;
    }

  }
  timeElements(entry) {
    this.timeDivision = entry;
    this.fp_ampm = false;
    this.validateElement();
  }


  /* Data model for time element */

  timeElement = {
    type: 'time',
    componentId: null,
    time: {
      isRequired: true,
      noDuplicate: false,
      label: '',
      answer: ''
    },
    elementToSubmit: {},
    isValidated: true,
    isValidFormat:true



  }

  /* Data model for time submit */

  timeSubmit: any = {
    componentId: null,
    type: 'time',
    time: {
      answer: ''
    }
  }


  ngOnInit() {
    setTimeout(() => {
      this.timeElement = this.data;
      this.timeSubmit.componentId = this.timeElement.componentId;
      if (!this.timeElement.hasOwnProperty('elementToSubmit')) {
        if(this.timeElement.time.answer){
          this.timeSubmit.time.answer =  this.timeElement.time.answer;
          this.timearr = this.timeSubmit.time.answer.split(":");
          this.hours = this.timearr[0],
            this.minutes = this.timearr[1],
            this.seconds = this.timearr[2]
       
        }
        this.timeElement.elementToSubmit = this.timeSubmit;
      } else {
        this.timeSubmit = this.timeElement.elementToSubmit;
        this.timearr = this.timeSubmit.time.answer.split(":");
        this.hours = this.timearr[0],
          this.minutes = this.timearr[1],
          this.seconds = this.timearr[2]
      }
      this.validateElement();
    }, 100)
  }
  /* Validating Element[Start] */

  validateElement(): void {
    this.timeElement.time.isRequired && (!this.hours || !this.minutes || !this.seconds || !this.timeDivision) ?
      this.timeElement.isValidated = false : this.timeElement.isValidated = true;
     
      this.timeElement.isValidated? this.timeElement.isValidFormat = true:this.timeElement.isValidFormat = false;


    if (this.timeElement.isValidated) {
      this.timeStringSubmit();
    }

  }
  /* Validating Element[End] */


}
