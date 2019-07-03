import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-section-break-preview',
  templateUrl: './section-break-preview.component.html',
  styleUrls: ['./section-break-preview.component.scss']
})
export class SectionBreakPreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

   /* Data model for number element */
  sectionElement={
     componentId: null,
     action: "create",
     type: "section",
     section: {
       title: "",
       description: ""
    }
  }

  currentElement:{};

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      this.sectionElement = this.currentElement['element']
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }
  

}
