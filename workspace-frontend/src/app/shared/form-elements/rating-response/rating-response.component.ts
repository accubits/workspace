import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-rating-response',
  templateUrl: './rating-response.component.html',
  styleUrls: ['./rating-response.component.scss']
})
export class RatingResponseComponent implements OnInit {
  @Input() data: any;
  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  /* data model for phone*/
  ratingElement = {
    "type": "rating",
    "componentId": null,
    "rating": {
      "isRequired": false,
      "noDuplicate": false,
      "label": "",
      "answer": null
    }
  }

  ngOnInit() {
    setTimeout(() => {
      this.ratingElement = this.data;
   }, 100)
  }


}
