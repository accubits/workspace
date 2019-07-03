import { Component, OnInit, Input } from '@angular/core';
import { DataService } from '../../services/data.service';


@Component({
  selector: 'app-form-submit-loader',
  templateUrl: './form-submit-loader.component.html',
  styleUrls: ['./form-submit-loader.component.scss']
})
export class FormSubmitLoaderComponent implements OnInit {

  @Input() data: any;
  currentElementIndex: string;
  localElementArray = <any>{ ...this.dataService.formElements };
  constructor(public dataService: DataService) { }
  currentElement = {};

  ngOnInit() {
    setTimeout(() => {
      this.currentElement =  this.data;
    }, 100);
  }

}
