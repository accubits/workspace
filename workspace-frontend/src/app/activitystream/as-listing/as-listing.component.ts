import { Component, OnInit, Input} from '@angular/core';
import { ActStreamDataService } from '../../shared/services/act-stream-data.service';
 
@Component({
  selector: 'app-as-listing',
  templateUrl: './as-listing.component.html',
  styleUrls: ['./as-listing.component.scss']
})
export class AsListingComponent implements OnInit {
  @Input() data: any;
  @Input('index') index;

  constructor(public actStreamDataService: ActStreamDataService) {}

  ngOnInit() {
  }
}
 