import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';
import { UtilityService } from '../../../shared/services/utility.service';


@Component({
  selector: 'app-email-submit',
  templateUrl: './email-submit.component.html',
  styleUrls: ['./email-submit.component.scss']
})
export class EmailSubmitComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(
    public dataService: DataService,
    public utilityService: UtilityService,
  ) { }

  /* Data model for email element */
  emailElement = {
    type: 'email',
    componentId: null,
    email: {
      isRequired: false,
      noDuplicate: false,
      label: '',
      answer: ''
    },
    elementToSubmit: {},
    isValidated: true,
    isValidFormat: true


  }
  /* Data model for email submit */

  emailSubmit: any = {
    componentId: null,
    type: 'email',
    email: {
      answer: ''
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.emailElement = this.data;
      this.emailSubmit.componentId = this.emailElement.componentId;
      if (!this.emailElement.hasOwnProperty('elementToSubmit')) {
        if(this.emailElement.email.answer){
          this.emailSubmit.email.answer =  this.emailElement.email.answer;
       
        }
        this.emailElement.elementToSubmit = this.emailSubmit;
      } else {
        this.emailSubmit = this.emailElement.elementToSubmit;
      }
      this.validateElement()

    }, 100)


  }
  /* Validating Element[Start] */
  validateElement(): void {
    (this.emailElement.email.isRequired && !this.emailSubmit.email.answer) ?
      this.emailElement.isValidated = false : this.emailElement.isValidated = true;

    if (this.emailElement.isValidated && this.emailSubmit.email.answer) {
      this.checkValidFormat();
    } else {
      this.emailElement.isValidFormat = true;
    }

  }
  /* Validating Element[End] */

  /* Checking email is in valid format */
  checkValidFormat(): void {
    this.utilityService.validateEmail(this.emailSubmit.email.answer) ?
      this.emailElement.isValidFormat = true : this.emailElement.isValidFormat = false;
  }


}
