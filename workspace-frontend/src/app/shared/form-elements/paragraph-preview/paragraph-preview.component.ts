import { Component, OnInit,Input} from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-paragraph-preview',
  templateUrl: './paragraph-preview.component.html',
  styleUrls: ['./paragraph-preview.component.scss']
})
export class ParagraphPreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  paragraphElement =   {
    componentId: null,
    action: "create",
    type: "paragraphText",
    paragraphText: {
      label: "",
      isRequired: false,
      noDuplicate: false
    }
  }
  currentElement:{}

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      this.paragraphElement = this.currentElement['element']
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }
  

}
