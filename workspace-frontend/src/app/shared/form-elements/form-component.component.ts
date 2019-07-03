import { Component, Input, Output, EventEmitter, OnInit } from '@angular/core';
import { DataService } from '../services/data.service';


@Component({
  selector: 'app-form-component',
  templateUrl: './form-component.component.html',
  styleUrls: ['./form-component.component.scss']
})
export class FormComponentComponent implements OnInit {
  @Output() deleteElementFromLocalArray = new EventEmitter<string>();
  @Input() data: any;
  currentElementIndex: number;
  localElementArray = <any>{ ...this.dataService.formElements };
  constructor(public dataService: DataService) { }

  ngOnInit() {
    setTimeout(() => {
      var clickedDetails = this.data.split(',');
      this.localElementArray[clickedDetails[0]] = true;
      this.currentElementIndex = clickedDetails[1];
      // console.log(clickedDetails);
    }, 100);
  }
  deleteElement(event) {
    console.log(event);
    this.deleteElementFromLocalArray.emit(event);
  }
}
