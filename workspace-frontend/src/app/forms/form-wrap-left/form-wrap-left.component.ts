import { Component, OnInit, Output, EventEmitter } from '@angular/core';
import { DataService } from '../../shared/services/data.service';
import { UtilityService } from '../../shared/services/utility.service';

@Component({
  selector: 'app-form-wrap-left',
  templateUrl: './form-wrap-left.component.html',
  styleUrls: ['./form-wrap-left.component.scss']
})
export class FormWrapLeftComponent implements OnInit {
  @Output() newElementSelected = new EventEmitter<string>();
 

  constructor(
    public dataService: DataService,
    public utilityService: UtilityService
  ) { }

  elementArray = [];
  dragStartSave = '';
  ngOnInit() {
  }
  form_elements={ type: 'item', id: 1, name:'dum' };

  elemetEnter(id) {
    this.newElementSelected.emit(id);
  }

  startDrag(element){
    console.log(element)
  }



}
