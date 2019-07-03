import { Component, OnInit, Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';

@Component({
  selector: 'app-form-response-loader',
  templateUrl: './form-response-loader.component.html',
  styleUrls: ['./form-response-loader.component.scss']
})
export class FormResponseLoaderComponent implements OnInit {
  @Input() data: any;
  currentElement = {};

  ngOnInit() {
    setTimeout(() => {
      this.currentElement =  this.data;
    }, 100);
  }


}
