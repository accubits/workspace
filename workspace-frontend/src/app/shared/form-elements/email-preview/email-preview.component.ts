import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-email-preview',
  templateUrl: './email-preview.component.html',
  styleUrls: ['./email-preview.component.scss']
})
export class EmailPreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
 
  /* Data model for email element */
  emailElement =  {
    componentId: null,
    action: 'create',
    type: 'email',
    email: {
      label: '',
      isRequired: true,
      noDuplicate: false
    }
  }

 currentElement:{}

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      this.emailElement = this.currentElement['element']
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }


}
