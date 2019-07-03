import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-rating-preview',
  templateUrl: './rating-preview.component.html',
  styleUrls: ['./rating-preview.component.scss']
})
export class RatingPreviewComponent implements OnInit {
  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
 
   /* Data model for rating element */
  ratingElement = {
    componentId: null,
    action: 'create',
    type: 'rating',
    rating: {
      label: '',
      isRequired: false
    }
  }

 currentElement:{}

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      this.ratingElement = this.currentElement['element']
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }

}
